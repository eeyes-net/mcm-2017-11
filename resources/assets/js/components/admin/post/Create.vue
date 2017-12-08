<template>
    <div>
        <h2>发布新公告</h2>
        <admin-post-editor :post-id="postId" @submit="store"></admin-post-editor>
        <!--<admin-post-create-form @stored="stored"></admin-post-create-form>-->
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
                });
            }
        }
    }
</script>