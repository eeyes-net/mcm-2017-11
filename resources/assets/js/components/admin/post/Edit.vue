<template>
    <div>
        <h2>编辑公告</h2>
        <admin-post-editor type="edit" :post="post" @submit="update"></admin-post-editor>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                post: {
                    id: 0,
                    title: '',
                    content: '',
                    created_at: '',
                    updated_at: ''
                }
            }
        },
        mounted() {
            this.getPost();
        },
        beforeRouteUpdate (to, from, next) {
            this.getPost(to);
            next();
        },
        methods: {
            getPost(route) {
                route = route || this.$router.currentRoute;
                axios.get('/api/admin/post/' + route.params.post_id)
                    .then(response => {
                        this.post = response.data;
                    });
            },
            update(form) {
                axios.put('/api/admin/post/' + form.id, {
                    title: form.title,
                    content: form.content,
                    created_at: form.created_at,
                }).then(response => {
                    alert('更新成功');
                    this.$router.back();
                });
            }
        }
    }
</script>