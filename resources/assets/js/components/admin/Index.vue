<template>
    <div>
        <h2>后台管理</h2>
        <b-card-group deck class="mb-3">
            <b-card header="用户数" text-variant="white" align="center" bg-variant="info">
                <router-link to="/admin/user">
                    <p class="card-text text-white">{{ users_count }}</p>
                </router-link>
            </b-card>
            <b-card header="队伍数" text-variant="white" align="center" bg-variant="success">
                <router-link to="/admin/team">
                    <p class="card-text text-white">{{ teams_count }}</p>
                </router-link>
            </b-card>
            <b-card header="招募数" text-variant="white" align="center" bg-variant="warning">
                <router-link to="/admin/recruit">
                    <p class="card-text text-white">{{ recruits_count }}</p>
                </router-link>
            </b-card>
            <b-card header="访问量" text-variant="white" align="center" bg-variant="secondary">
                <p class="card-text text-white">{{ visit_logs_count }}</p>
            </b-card>
        </b-card-group>
        <b-row>
            <b-col :md="6" class="mb-2">
                <h3>新增用户</h3>
                <b-table hover small responsive striped :items="users_new" :fields="usersNewFields">
                    <template slot="name" slot-scope="data">
                        <router-link :to="`/admin/user/${data.item.id}/edit`">
                            {{ data.value }}
                        </router-link>
                    </template>
                </b-table>
            </b-col>
            <b-col :md="6" class="mb-2">
                <h3>报名情况</h3>
                <b-table hover small responsive striped :items="matches" :fields="matchesFields">
                    <template slot="title" slot-scope="data">
                        <router-link :to="`/admin/match/${data.item.id}/edit`">
                            {{ data.value }}
                        </router-link>
                    </template>
                    <template slot="teams_count" slot-scope="data">
                        <router-link :to="`/admin/match/${data.item.id}/team`">
                            {{ data.value }}
                        </router-link>
                    </template>
                </b-table>
            </b-col>
        </b-row>
        <b-row>
            <b-col :size="12" class="mb-2">
                <h3>每日用户增长</h3>
                <div class="admin-layouts-bar-chart-container">
                    <admin-layouts-bar-chart :data="users_growth" label="每日用户增长" class="admin-layouts-bar-chart"></admin-layouts-bar-chart>
                </div>
            </b-col>
        </b-row>
        <b-row>
            <b-col :size="12" class="mb-2">
                <h3>最近竞赛每日报名人数</h3>
                <div class="admin-layouts-bar-chart-container">
                    <admin-layouts-bar-chart :data="match_teams_growth.data" :label="match_teams_growth.match.title" class="admin-layouts-bar-chart"></admin-layouts-bar-chart>
                </div>
            </b-col>
        </b-row>
        <b-row>
            <b-col :size="12" class="mb-2">
                <h3>最近竞赛学院分布情况</h3>
                <div class="admin-layouts-bar-chart-container">
                    <admin-layouts-bar-chart :data="match_department_users_count.data" :label="match_department_users_count.match.title" class="admin-layouts-bar-chart"></admin-layouts-bar-chart>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
                users_count: 0,
                teams_count: 0,
                visit_logs_count: 0,
                recruits_count: 0,
                users_new: [],
                users_growth: {},
                match_teams_growth: {
                    match: '',
                    data: {}
                },
                match_department_users_count: {
                    match: '',
                    data: {}
                },
                matches: []
            };
        },
        mounted() {
            this.get();
        },
        methods: {
            get() {
                this.errors = [];
                axios.get('/api/admin').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        let data = response.data.data;
                        this.users_count = data.users_count;
                        this.teams_count = data.teams_count;
                        this.visit_logs_count = data.visit_logs_count;
                        this.recruits_count = data.recruits_count;
                        this.users_new = data.users_new;
                        this.users_growth = data.users_growth;
                        this.matches = data.matches;
                        this.match_teams_growth = data.match_teams_growth;
                        this.match_department_users_count = data.match_department_users_count;
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            }
        },
        computed: {
            usersNewFields() {
                return [
                    {key: 'name', label: '姓名'},
                    {key: 'stu_id', label: '学号'},
                    {key: 'class', label: '班级'},
                    {key: 'created_at.diff', label: '创建时间'}
                ];
            },
            matchesFields() {
                return [
                    {key: 'title', label: '标题'},
                    {key: 'teams_count', label: '报名队伍数'},
                    {key: 'created_at.diff', label: '创建时间'}
                ];
            }
        }
    };
</script>