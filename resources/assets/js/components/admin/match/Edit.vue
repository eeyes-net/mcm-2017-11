<template>
    <div>
        <h2>编辑竞赛</h2>
        <admin-match-editor type="edit" :match="match" @submit="update"></admin-match-editor>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                match: {
                    id: '',
                    title: '',
                    expired_at: '',
                    status: 'close',
                    created_at: '',
                    updated_at: ''
                }
            }
        },
        mounted() {
            this.getMatch();
        },
        beforeRouteUpdate (to, from, next) {
            this.getMatch(to);
            next();
        },
        methods: {
            getMatch(route) {
                route = route || this.$router.currentRoute;
                axios.get('/api/admin/match/' + route.params.match_id)
                    .then(response => {
                        this.match = response.data;
                    });
            },
            update(form) {
                axios.put('/api/admin/match/' + form.id, {
                    title: form.title,
                    expired_at: form.expired_at,
                    created_at: form.created_at,
                    status: form.status,
                }).then(response => {
                    alert('更新成功');
                    this.$router.back();
                });
            }
        }
    }
</script>