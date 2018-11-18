<template>
    <div>
        <h2>
            队伍列表
            <!--<span class="float-right">-->
                <!--<b-button variant="primary" @click="create">创建新队伍</b-button>-->
            <!--</span>-->
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
        methods: {
            showUser(user) {
                this.$router.push('/admin/user/' + user.id + '/edit');
            },
            getTeams(route) {
                route = route || this.$router.currentRoute;
                let page = route.query.page || 1;
                axios.get('/api/admin/team', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.teamsData = response.data;
                });
            },
            // create() {
            //     this.$router.push('/admin/team/create');
            // },
            edit(team) {
                this.$router.push('/admin/team/' + team.id + '/edit');
            },
            destory(team) {
                if (confirm('您确定要删除 #' + team.id + ' 吗？')) {
                    axios.delete('/api/admin/team/' + team.id)
                        .then(response => {
                            if (response.data) {
                                alert('删除成功');
                            } else {
                                console.log(response);
                                alert('删除失败');
                            }
                            this.getTeams();
                        })
                        .catch(error => {
                            console.log(error);
                            alert('删除失败');
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/team?page=' + page);
            }
        }
    }
</script>
