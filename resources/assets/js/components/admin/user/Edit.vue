<template>
    <div>
        <h2>编辑用户信息</h2>
        <admin-user-editor :user="user" @submit="update"></admin-user-editor>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {
                    id: 0,
                    username: '',
                    stu_id: '',
                    name: '',
                    department: '',
                    major: '',
                    class: '',
                    contact: '',
                    email: '',
                    group: '',
                    created_at: '',
                    updated_at: ''
                }
            }
        },
        mounted() {
            this.getUser();
        },
        beforeRouteUpdate(to, from, next) {
            this.getUser(to);
            next();
        },
        methods: {
            getUser(route) {
                route = route || this.$router.currentRoute;
                axios.get('/api/admin/user/' + route.params.user_id)
                    .then(response => {
                        this.user = response.data;
                    });
            },
            update(form) {
                axios.put('/api/admin/user/' + form.id, form)
                    .then(response => {
                        alert('更新成功');
                        this.$router.back();
                    });
            }
        }
    }
</script>