<template>
    <div>
        <h2>
            参赛队伍列表
            <span class="float-right">
                <b-button variant="primary" @click="allocNumber">分配编号</b-button>
                <b-button variant="outline-info" @click="snapshot">生成快照</b-button>
            </span>
        </h2>
        <admin-team-table :teams="teamsData.data" @showUser="showUser" @edit="edit" @destory="destory"></admin-team-table>
        <b-pagination :total-rows="teamsData.total" v-model="teamsData.current_page" :per-page="teamsData.per_page" :limit="10" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                teamsData: {}
            };
        },
        mounted() {
            this.getTeams();
        },
        beforeRouteUpdate(to, from, next) {
            this.getTeams(to);
            next();
        },
        computed: {
            matchId() {
                return this.$router.currentRoute.params.match_id;
            }
        },
        methods: {
            showUser(user) {
                this.$router.push('/admin/user/' + user.id + '/edit');
            },
            getTeams(route) {
                route = route || this.$router.currentRoute;
                let page = route.query.page || 1;
                axios.get('/api/admin/match/' + route.params.match_id + '/team', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.teamsData = response.data;
                });
            },
            create() {
                this.$router.push('/admin/match/' + this.matchId + '/apply');
            },
            edit(team) {
                this.$router.push('/admin/team/' + team.id + '/edit');
            },
            destory(team) {
                if (confirm('您确定要取消 #' + team.id + ' 的报名吗？')) {
                    axios.delete('/api/admin/match/' + this.matchId + '/team/' + team.id)
                        .then(response => {
                            this.getTeams();
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/match/' + this.matchId + '/team?page=' + page);
            },
            allocNumber() {
                let route = this.$router.currentRoute;
                axios.post('/api/admin/match/' + route.params.match_id + '/team/alloc_number').then(response => {
                    if (response.data.id) {
                        alert('编号分配完成');
                        this.getTeams();
                    } else {
                        alert('出现了一些问题，请重试。');
                    }
                }).catch(error => {
                    alert('出现了一些问题，请重试。');
                });
            },
            snapshot() {
                let route = this.$router.currentRoute;
                axios.post('/api/admin/match/' + route.params.match_id + '/snapshot').then(response => {
                    if (response.data.filename) {
                        if (response.data.errors.length) {
                            alert('生成快照成功，但数据存在以下问题（下载的表格中的第二张表格也有此错误信息）：\n' + response.data.errors.join('\n'));
                        } else {
                            alert('生成快照成功');
                        }
                    } else {
                        alert('出现了一些问题，请重试。');
                    }
                }).catch(error => {
                    alert('出现了一些问题，请重试。');
                });
            }
        }
    };
</script>
