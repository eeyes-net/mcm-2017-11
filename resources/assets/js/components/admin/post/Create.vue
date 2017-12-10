<template>
    <div>
        <h2>发布新公告</h2>
        <admin-post-editor @submit="store"></admin-post-editor>
    </div>
</template>

<script>
    export default {
        methods: {
            store(form) {
                axios.post('/api/admin/post', {
                    title: form.title,
                    content: form.content
                }).then(response => {
                    alert('保存成功');
                    this.$router.back();
                });
            }
        }
    }
</script>