<template>
    <div>
        <b-form @submit.prevent="$emit('submit', form)">
            <b-form-group label="用户名" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.username" disabled></b-form-input>
            </b-form-group>
            <b-form-group label="学号" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.stu_id" required></b-form-input>
            </b-form-group>
            <b-form-group label="姓名" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.name" required></b-form-input>
            </b-form-group>
            <b-form-group label="学院" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.department" required></b-form-input>
            </b-form-group>
            <b-form-group label="专业" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.major" required></b-form-input>
            </b-form-group>
            <b-form-group label="班级" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.class" required></b-form-input>
            </b-form-group>
            <b-form-group label="联系方式" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.contact" required></b-form-input>
            </b-form-group>
            <b-form-group label="E-mail" horizontal :label-cols="3">
                <b-form-input type="email" v-model="form.email" required></b-form-input>
            </b-form-group>
            <b-form-group label="参赛与获奖经历" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.experience" required></b-form-input>
            </b-form-group>
            <b-form-group label="教练姓名" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.coach_name" required></b-form-input>
            </b-form-group>
            <b-form-group label="用户权限" horizontal :label-cols="3">
                <b-form-select v-model="form.group" :options="options"></b-form-select>
            </b-form-group>
            <b-button type="submit" variant="primary">更新</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: [
            'user'
        ],
        data() {
            return {
                form: {
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
                },
                options: [
                    { value: 'student', text: '学生' },
                    { value: 'admin', text: '管理员' },
                    { value: 'banned', text: '禁止登陆' }
                ]
            }
        },
        watch: {
            user() {
                this.form.id = this.user.id;
                this.form.username = this.user.username;
                this.form.stu_id = this.user.stu_id;
                this.form.name = this.user.name;
                this.form.department = this.user.department;
                this.form.major = this.user.major;
                this.form.class = this.user.class;
                this.form.contact = this.user.contact;
                this.form.email = this.user.email;
                this.form.experience = this.user.experience;
                this.form.coach_name = this.user.coach_name;
                this.form.group = this.user.group;
            }
        }
    }
</script>