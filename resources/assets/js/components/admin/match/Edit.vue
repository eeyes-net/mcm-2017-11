<template>
    <div>
        <h2>编辑竞赛</h2>
        <admin-match-editor type="edit" :match-id="matchId" @submit="update"></admin-match-editor>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                matchId: 0
            }
        },
        mounted() {
            this.matchId = this.$router.currentRoute.params.match_id;
        },
        methods: {
            update(form) {
                axios.put('/api/admin/match/' + this.matchId, {
                    title: form.title,
                    expired_at: form.expired_at,
                    created_at: form.created_at,
                    status: form.status,
                }).then(response => {
                    alert('更新成功');
                });
            }
        }
    }
</script>