<template>
    <div>
        <h2>编辑招募信息</h2>
        <admin-recruit-editor :recruit="recruit" @submit="update"></admin-recruit-editor>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                recruit: {
                    id: 0,
                    team_id: 0,
                    tags: '',
                    members: '',
                    description: '',
                    contact: '',
                    created_at: '',
                    updated_at: ''
                }
            }
        },
        mounted() {
            this.getRecruit();
        },
        beforeRouteUpdate(to, from, next) {
            this.getRecruit(to);
            next();
        },
        methods: {
            getRecruit(route) {
                route = route || this.$router.currentRoute;
                axios.get('/api/admin/recruit/' + route.params.recruit_id)
                    .then(response => {
                        this.recruit = response.data;
                    });
            },
            update(form) {
                axios.put('/api/admin/recruit/' + form.id, form)
                    .then(response => {
                        alert('更新成功');
                        this.$router.back();
                    });
            }
        }
    }
</script>