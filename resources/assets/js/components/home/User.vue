<template>
    <div class="home-user">
        <h2>个人信息
            <span class="float-right">
                <b-btn @click="edit">编辑</b-btn>
            </span>
        </h2>

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
                <b-col :cols="12" :md="9" :lg="10">{{ user.coach }}</b-col>
            </b-row>
            <b-row v-show="user.group == 'admin'">
                <b-col :cols="12" :md="3" :lg="2" class="font-weight-bold">用户组</b-col>
                <b-col :cols="12" :md="9" :lg="10">管理员（<a href="/admin" target="_blank">点击进入后台管理</a>）</b-col>
            </b-row>
        </b-card>

        <b-modal title="编辑个人信息" size="lg" class="user-edit-modal" v-model="modalShow" ok-title="保存" cancel-title="取消" @ok="handleOk" @hidden="modalHidden">
            <b-alert variant="danger" dismissible :show="errors.length > 0"><p v-for="error in errors">{{ error }}</p></b-alert>
            <b-form @submit="update()">
                <b-form-group horizontal :label-cols="3" label="姓名">
                    <b-form-input :disabled="true" placeholder="请输入您的姓名" v-model="form.name"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="学号">
                    <b-form-input :disabled="true" placeholder="请输入您的学号" v-model="form.stu_id"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="班级">
                    <b-form-input :disabled="true" placeholder="请输入您的班级" v-model="form.class"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="学院">
                    <b-form-input :disabled="true" placeholder="" v-model="form.department"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="专业">
                    <b-form-input :disabled="true" placeholder="请输入您的专业全称" v-model="form.major"></b-form-input>
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
                    <b-form-input placeholder="您之前的教练姓名" v-model="form.coach"></b-form-input>
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
                    class: '',
                    department: '',
                    major: '',
                    email: '',
                    contact: '',
                    group: '',
                    created_at: '',
                    updated_at: '',
                    tmp: ''
                },
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
                    created_at: '',
                    updated_at: ''
                },
                modalShow: false,
                errors: []
            };
        },
        mounted() {
            this.get();
        },
        methods: {
            get() {
                axios.get('/api/user').then(response => {
                    this.user = response.data;
                });
            },
            edit() {
                this.form.id = this.user.id;
                this.form.username = this.user.username;
                this.form.stu_id = this.user.stu_id;
                this.form.name = this.user.name;
                this.form.department = this.user.department;
                this.form.major = this.user.major;
                this.form.class = this.user.class;
                this.form.contact = this.user.contact;
                this.form.email = this.user.email;
                this.modalShow = true;
            },
            update() {
                let form = this.form;
                axios.put('/api/user', {
                    contact: form.contact,
                    email: form.email
                }).then(response => {
                    if (response.data.id) {
                        this.user = response.data;
                        this.errors = [];
                        this.get();
                        this.modalShow = false;
                    } else if (response.data.message) {
                        this.errors = _.flatten(_.toArray(error.response.data));
                    } else {
                        this.errors = ['出现了一些问题，请重试。'];
                    }
                }).catch(error => {
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data));
                    } else {
                        this.errors = ['出现了一些问题，请重试。'];
                    }
                });
            },
            handleOk(e) {
                e.preventDefault();
                this.update();
            },
            modalHidden() {
                $('.sidebar').width('');
            }
        },
        watch: {
            modalShow(val) {
                if (val) {
                    $('.sidebar').width($('.sidebar').width());
                }
            }
        }
    };
</script>
