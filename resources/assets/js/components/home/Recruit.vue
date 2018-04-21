<template>
    <div class="home-recruit">
        <h2>我的招募</h2>

        <layouts-error :show="!modalShow" :errors="errors"></layouts-error>

        <b-row>
            <b-col md="6" lg="4" v-for="recruit in recruits" :key="recruit.id">
                <home-recruit-card :recruit="recruit" @edit="edit" @destroy="destroy"></home-recruit-card>
            </b-col>
        </b-row>

        <b-modal title="修改招募信息" class="recruit-edit-modal" v-model="modalShow" ok-title="保存" cancel-title="取消" @ok="handleOk" @hidden="modalHidden">
            <layouts-error :errors="errors"></layouts-error>
            <b-form @submit="update()">
                <b-form-group horizontal :label-cols="3" label="队伍编号">
                    <b-form-input :disabled="true" placeholder="请输入您的姓名" v-model="form.team_id"></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="选择招募类型">
                    <b-form-checkbox-group v-model="form.tags" :options="recruitTagOptions"></b-form-checkbox-group>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="当前队员">
                    <b-form-input placeholder="请留下您的队伍中当前队员信息" v-model="form.members" :rows="3" disabled></b-form-input>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="队伍描述">
                    <b-form-textarea placeholder="请添加您的队伍描述，不超过48个字" v-model="form.description" :rows="3"></b-form-textarea>
                </b-form-group>
                <b-form-group horizontal :label-cols="3" label="联系方式">
                    <b-form-input placeholder="请留下您的联系方式" v-model="form.contact"></b-form-input>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                recruitTagOptions: [],
                recruits: [],
                form: {
                    id: 0,
                    team_id: 0,
                    tags: [],
                    members: '',
                    description: '',
                    contact: ''
                },
                modalShow: false,
                errors: []
            };
        },
        mounted() {
            this.get();
            this.getRecruitTags();
        },
        computed: {
            recruitsbyId() {
                return _.keyBy(this.recruits, 'id');
            }
        },
        methods: {
            get() {
                this.errors = [];
                axios.get('/api/recruit/current_user').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        this.recruits = response.data.data;
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            getRecruitTags() {
                this.errors = [];
                axios.get('/api/recruit/tags').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        this.recruitTagOptions = response.data.data;
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            edit(recruit) {
                recruit = this.recruitsbyId[recruit.id];
                this.errors = [];
                this.form.id = recruit.id;
                this.form.team_id = recruit.team_id;
                this.form.tags = recruit.tags;
                this.form.members = recruit.members;
                this.form.description = recruit.description;
                this.form.contact = recruit.contact;
                this.modalShow = true;
            },
            update() {
                this.errors = [];
                let form = this.form;
                axios.put('/api/recruit/' + form.id, {
                    tags: form.tags,
                    members: form.members,
                    description: form.description,
                    contact: form.contact
                }).then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        this.errors = [];
                        this.get();
                        this.modalShow = false;
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            destroy(recruit) {
                if (confirm('确定删除这条招募？\n\n'
                    + '队伍编号：' + recruit.team_id + '\n'
                    + '招募类型：' + recruit.tags + '\n'
                    + '当前队员：' + recruit.members + '\n'
                    + '队伍描述：' + recruit.description + '\n'
                    + '联系方式：' + recruit.contact)) {
                    this.errors = [];
                    axios.delete('/api/recruit/' + recruit.id).then(response => {
                        this.errors = [];
                        if (response.data.data) {
                            this.errors = [];
                            this.get();
                            this.modalShow = false;
                        } else {
                            this.errors = response;
                        }
                    }).catch(error => {
                        this.errors = error;
                    });
                }
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
                this.errors = [];
                if (val) {
                    $('.sidebar').width($('.sidebar').width());
                }
            }
        }
    };
</script>