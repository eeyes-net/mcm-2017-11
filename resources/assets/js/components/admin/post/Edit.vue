<template>
    <div>
        <h2>编辑公告</h2>
        <admin-post-editor type="edit" :post-id="postId" @submit="update"></admin-post-editor>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                postId: 0
            }
        },
        mounted() {
            this.postId = this.$router.currentRoute.params.post_id;
        },
        methods: {
            update(form) {
                axios.post('/api/admin/post/' + this.postId, {
                    title: form.title,
                    content: form.content,
                    created_at: form.created_at,
                }).then(response => {
                    alert('更新成功');
                });
            }
        }
    }
</script>