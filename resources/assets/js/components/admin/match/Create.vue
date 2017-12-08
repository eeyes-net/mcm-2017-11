<template>
    <div>
        <h2>发布新竞赛</h2>
        <admin-match-editor @submit="store"></admin-match-editor>
    </div>
</template>

<script>
    export default {
        methods: {
            store(form) {
                axios.post('/api/admin/match', {
                    title: form.title,
                    expired_at: form.expired_at,
                    status: form.status
                }).then(response => {
                    alert('保存成功');
                });
            }
        }
    }
</script>