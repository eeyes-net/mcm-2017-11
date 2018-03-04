<template>
    <div class="home-team">
        <h2>我的队伍</h2>
        <b-card :title="'队伍编号：' + team.id" v-for="team in teams" :key="team.id">
            <b-table small responsive :items="team.users" :fields="fields"></b-table>
        </b-card>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fields: [
                    {key: 'position', label: '身份'},
                    {key: 'name', label: '姓名'},
                    {key: 'stu_id', label: '学号'},
                    {key: 'class', label: '班级'},
                    {key: 'department', label: '学院'},
                    {key: 'contact', label: '联系电话'},
                    {key: 'email', label: '邮箱'}
                ],
                teams: []
            };
        },
        mounted() {
            this.getTeams();
        },
        methods: {
            getTeams() {
                axios.get('/api/team').then(response => {
                    this.teams = response.data;
                });
            }
        }
    };
</script>
