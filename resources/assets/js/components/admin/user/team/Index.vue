<template>
    <div>
        <h2>
            队伍列表
            <span class="float-right">
                <b-button variant="primary" @click="create">创建新队伍</b-button>
            </span>
        </h2>
        <admin-team-table :teams="teams" @showUser="showUser" @edit="edit" @destory="destory"></admin-team-table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                teams: []
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
                axios.get('/api/admin/user/' + route.params.user_id + '/team')
                    .then(response => {
                        this.teams = response.data;
                    });
            },
            edit(team) {
                this.$router.push('/admin/team/' + team.id + '/edit');
            }
        }
    }
</script>
