<template>
    <div>
        <h2>
            参赛队伍列表
            <span class="float-right">
                <b-button variant="primary" @click="allocNumber">分配编号</b-button>
                <b-button variant="outline-info" @click="download">下载名单</b-button>
            </span>
        </h2>
        <layouts-error :errors="errors"></layouts-error>
        <admin-team-table :teams="teamsData.data" @showUser="showUser" @edit="edit" @destory="destory"></admin-team-table>
        <b-pagination :total-rows="teamsData.total" v-model="teamsData.current_page" :per-page="teamsData.per_page" :limit="10" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
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
                this.errors = [];
                axios.get('/api/admin/match/' + route.params.match_id + '/team', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.errors = [];
                    this.teamsData = response.data;
                }).catch(error => {
                    this.errors = error;
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
                    this.errors = [];
                    axios.delete('/api/admin/match/' + this.matchId + '/team/' + team.id)
                        .then(response => {
                            this.errors = [];
                            this.getTeams();
                        })
                        .catch(error => {
                            this.errors = error;
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/match/' + this.matchId + '/team?page=' + page);
            },
            allocNumber() {
                let route = this.$router.currentRoute;
                this.errors = [];
                axios.post('/api/admin/match/' + route.params.match_id + '/team/alloc_number').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        if (response.data.data.errors.length) {
                            this.errors = _.concat(['分配编号成功，但数据存在以下问题：'], response.data.data.errors);
                        } else {
                            alert('分配编号成功');
                        }
                        this.getTeams();
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            download() {
                let route = this.$router.currentRoute;
                this.errors = [];
                axios.post('/api/admin/match/' + route.params.match_id + '/snapshot').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        let data = response.data.data;
                        if (data.errors.length) {
                            this.errors = _.concat(['生成快照成功，但数据存在以下问题（下载的表格中也包含此错误信息说明）：'], data.errors);
                        }

                        function downloadURI(url) {
                            let iframe = document.createElement('iframe');
                            iframe.style.display = 'none';
                            iframe.src = url;
                            document.body.appendChild(iframe);
                        }

                        downloadURI('/api/admin/match/snapshot/download?filename=' + encodeURIComponent(data.filename));
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            }
        }
    };
</script>
