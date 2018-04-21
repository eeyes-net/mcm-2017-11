<template>
    <div class="home-user">
        <h2>个人信息
            <span class="float-right">
                <b-btn @click="edit">编辑</b-btn>
            </span>
        </h2>

        <layouts-error :show="!modalShow" :errors="errors"></layouts-error>

        <b-card class="home-user-info">
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">姓名</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.name }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">学号</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.stu_id }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">学院</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.department }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">专业</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.major }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">班级</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.class }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">邮箱</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.email }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">联系电话</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.contact }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">参赛与获奖经历</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.experience }}</b-col>
            </b-row>
            <b-row>
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">教练姓名</b-col>
                <b-col :cols="12" :md="9" :lg="10">{{ user.coach_name }}</b-col>
            </b-row>
        </b-card>

        <b-modal title="编辑个人信息" class="user-edit-modal" v-model="modalShow" ok-title="保存" cancel-title="取消" @ok="update" @hidden="modalHidden">
            <layouts-error :errors="errors"></layouts-error>
            <b-form @submit="update">
                <b-form-group horizontal :label-cols="3" label="姓名">
                    <b-form-input :disabled="true" placeholder="请输入您的姓名" v-model="user.name"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="学号">
                    <b-form-input :disabled="true" placeholder="请输入您的学号" v-model="user.stu_id"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="班级">
                    <b-form-input :disabled="true" placeholder="请输入您的班级" v-model="user.class"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="学院">
                    <b-form-input :disabled="true" placeholder="请输入您的学院" v-model="user.department"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="专业">
                    <b-form-input :disabled="true" placeholder="请输入您的专业全称" v-model="user.major"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="邮箱">
                    <b-form-input :required="true" placeholder="请输入您的邮箱" v-model="form.email"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="联系电话">
                    <b-form-input placeholder="请输入您的联系电话" v-model="form.contact"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="参赛与获奖经历">
                    <b-form-input placeholder="您之前参加数学建模竞赛经历与获奖情况" v-model="form.experience"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="教练姓名">
                    <b-form-input placeholder="您之前的教练姓名" v-model="form.coach_name"></b-form-input>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {
                    id: 0,
                    username: '',
                    name: '',
                    stu_id: '',
                    department: '',
                    major: '',
                    class: '',
                    contact: '',
                    email: '',
                    experience: '',
                    coach_name: '',
                    group: ''
                },
                form: {
                    contact: '',
                    email: '',
                    experience: '',
                    coach_name: ''
                },
                modalShow: false,
                errors: []
            };
        },
        mounted() {
            this.get();
        },
        methods: {
            mergeUserData(data) {
                this.user.id = data.id;
                this.user.username = data.username;
                this.user.name = data.name;
                this.user.stu_id = data.stu_id;
                this.user.department = data.department;
                this.user.major = data.major;
                this.user.class = data.class;
                this.user.contact = data.contact;
                this.user.email = data.email;
                this.user.experience = data.experience;
                this.user.coach_name = data.coach_name;
                this.user.group = data.group;
            },
            get() {
                this.errors = [];
                axios.get('/api/user').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        this.mergeUserData(response.data.data);
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            edit() {
                this.form.contact = this.user.contact;
                this.form.email = this.user.email;
                this.form.experience = this.user.experience;
                this.form.coach_name = this.user.coach_name;
                this.errors = [];
                this.modalShow = true;
            },
            update(e) {
                e.preventDefault();
                let form = this.form;
                this.errors = [];
                axios.put('/api/user', {
                    contact: form.contact,
                    email: form.email,
                    experience: form.experience,
                    coach_name: form.coach_name
                }).then(response => {
                    if (response.data.data) {
                        this.errors = [];
                        this.mergeUserData(response.data.data);
                        this.get();
                        this.modalShow = false;
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            modalHidden() {
                $('.sidebar').width('');
            }
        },
        watch: {
            modalShow(val) {
                this.errors = [];
                if (val) {
                    $('.sidebar').width($('.sidebar').width());
                }
            }
        }
    };
</script>
