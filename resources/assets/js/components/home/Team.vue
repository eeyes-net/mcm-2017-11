<template>
    <div class="home-team">
        <h2>我的队伍
            <span class="float-right">
                <b-btn @click="create">创建新队伍</b-btn>
            </span>
        </h2>
        <b-alert variant="danger" dismissible :show="!modalCreateShow && !modalEditShow && errors.length > 0"><p v-for="error in errors">{{ error }}</p></b-alert>
        <b-card v-for="team in teams" :key="team.id">
            <h4 class="card-title">队伍编号：{{ team.team_id ? team.team_id : '未分配' }}
                <span class="float-right">
                    <b-button size="sm" @click="edit(team)" v-if="team.is_lead">编辑成员</b-button>
                    <b-button size="sm" variant="primary" @click="verify(team)" v-if="!team.is_verified">同意邀请</b-button>
                    <b-button size="sm" variant="danger" @click="destroy(team)">退出队伍</b-button>
                </span>
            </h4>
            <b-table small responsive :items="team.users" :fields="fields"></b-table>
        </b-card>

        <b-modal title="创建新队伍" class="team-create-modal" v-model="modalCreateShow" ok-title="创建" cancel-title="取消" @ok="store" @hidden="modalHidden()">
            <b-alert variant="danger" dismissible :show="errors.length > 0"><p v-for="error in errors">{{ error }}</p></b-alert>
            <b-form @submit="store">
                <b-form-group horizontal :label-cols="2" :label="'队员' + (index + 1)" label-class="font-weight-bold" class="mb-0" v-for="(user, index) in form.users" :key="index">
                    <b-form-group horizontal :label-cols="2" label="姓名">
                        <b-form-input placeholder="姓名" v-model="user.name"></b-form-input>
                    </b-form-group>
                    <b-form-group horizontal :label-cols="2" label="学号">
                        <b-form-input placeholder="学号" v-model="user.stu_id"></b-form-input>
                    </b-form-group>
                    <b-button variant="danger" class="mb-3" @click="formRemoveUser(user)">从队伍中移除 {{ user.name }}</b-button>
                </b-form-group>
                <b-form-group horizontal :label-cols="2" label="新成员" label-class="font-weight-bold" class="mb-0" v-show="form.users.length < 2">
                    <b-button variant="primary" @click="formAddUser()">添加新成员</b-button>
                </b-form-group>
            </b-form>
        </b-modal>

        <b-modal title="编辑队伍成员" class="team-create-modal" v-model="modalEditShow" ok-title="保存" cancel-title="取消" @ok="update" @hidden="modalHidden()">
            <b-alert variant="danger" dismissible :show="errors.length > 0"><p v-for="error in errors">{{ error }}</p></b-alert>
            <b-form @submit="update">
                <b-form-group horizontal :label-cols="2" :label="'成员' + (index + 1)" label-class="font-weight-bold" class="mb-0" v-for="(user, index) in form.users" :key="index">
                    <b-form-group horizontal :label-cols="2" label="姓名">
                        <b-form-input placeholder="姓名" v-model="user.name"></b-form-input>
                    </b-form-group>
                    <b-form-group horizontal :label-cols="2" label="学号">
                        <b-form-input placeholder="学号" v-model="user.stu_id"></b-form-input>
                    </b-form-group>
                    <b-button variant="danger" class="mb-3" @click="formRemoveUser(user)">从队伍中移除 {{ user.name }}</b-button>
                </b-form-group>
                <b-form-group horizontal :label-cols="2" label="新成员" label-class="font-weight-bold" class="mb-0" v-show="form.users.length < 2">
                    <b-button variant="primary" @click="formAddUser()">添加新成员</b-button>
                </b-form-group>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
                fields: [
                    {key: 'positionText', label: '身份', sortable: true},
                    {key: 'name', label: '姓名'},
                    {key: 'stu_id', label: '学号'},
                    {key: 'class', label: '班级'},
                    {key: 'department', label: '学院'},
                    {key: 'contact', label: '联系电话'},
                    {key: 'email', label: '邮箱'}
                ],
                positionOptions: [
                    {value: 'leader', text: '队长'},
                    {value: 'member', text: '队员'}
                ],
                teams: [],
                form: {
                    team_id: 0,
                    users: []
                },
                modalCreateShow: false,
                modalEditShow: false
            };
        },
        mounted() {
            this.get();
            if (window.location.hash === '#new_team') {
                this.create();
            }
        },
        computed: {
            positionOptionsMap() {
                return _.keyBy(this.positionOptions, 'value');
            }
        },
        methods: {
            get() {
                axios.get('/api/team').then(response => {
                    this.teams = response.data;
                    for (let i = 0; i < this.teams.length; ++i) {
                        let team = this.teams[i];
                        for (let j = 0; j < team.users.length; ++j) {
                            let user = team.users[j];
                            user.positionText = this.positionOptionsMap[user.position].text;
                            if (user.position === 'leader') {
                                user._rowVariant = 'secondary';
                            } else if (user.status === 'verifying') {
                                user._rowVariant = 'warning';
                            }
                        }
                    }
                });
            },
            create() {
                this.formClear();
                for (let i = 0; i < 2; ++i) {
                    this.formAddUser();
                }
                this.errors = [];
                this.modalCreateShow = true;
            },
            store(e) {
                e.preventDefault();
                axios.post('/api/team', this.form).then(response => {
                    if (response.data.id) {
                        this.errors = [];
                        this.get();
                        this.modalCreateShow = false;
                    } else if (response.data.message) {
                        this.errors = _.flatten(_.toArray(response.data));
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
            edit(team) {
                this.formClear();
                this.form.team_id = team.id;
                for (let i = 0; i < team.users.length; ++i) {
                    let user = team.users[i];
                    if (user.position !== 'leader') {
                        this.form.users.push({
                            id: user.id,
                            name: user.name,
                            stu_id: user.stu_id
                        });
                    }
                }
                this.errors = [];
                this.modalEditShow = true;
            },
            update(e) {
                e.preventDefault();
                this.errors = [];
                axios.put('/api/team/' + this.form.team_id, this.form).then(response => {
                    if (response.data.id) {
                        this.errors = [];
                        this.get();
                        this.modalEditShow = false;
                    } else if (response.data.message) {
                        this.errors = _.flatten(_.toArray(response.data));
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
            verify(team) {
                this.errors = [];
                axios.post('/api/team/' + team.id + '/verify').then(response => {
                    if (response.data.id) {
                        this.errors = [];
                        this.get();
                        alert('已加入队伍（id = ' + response.data.id + '）');
                    } else if (response.data.message) {
                        this.errors = _.flatten(_.toArray(response.data));
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
            destroy(team) {
                if (confirm('您确定退出队伍' + team.id)) {
                    axios.delete('/api/team/' + team.id).then(response => {
                        if (response.data.id) {
                            this.user = response.data;
                            this.errors = [];
                            this.get();
                        } else if (response.data.message) {
                            this.errors = _.flatten(_.toArray(response.data));
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
                }
            },
            formClear() {
                this.form.team_id = 0;
                /** @link https://vuejs.org/v2/guide/list.html#Array-Change-Detection */
                this.form.users.splice(0, this.form.users.length);
            },
            formAddUser() {
                if (this.form.users.length >= 2) {
                    this.errors.push('除队长外，队伍中最多 2 名成员');
                    return;
                }
                /** @link https://vuejs.org/v2/guide/list.html#Array-Change-Detection */
                this.form.users.push({
                    id: -this.form.users.length,
                    name: '',
                    stu_id: ''
                });
            },
            formRemoveUser(user) {
                let i = _.findIndex(this.form.users, {id: user.id});
                this.form.users.splice(i, 1);
            },
            modalHidden() {
                this.errors = [];
                $('.sidebar').width('');
                /** @link https://stackoverflow.com/questions/1397329/how-to-remove-the-hash-from-window-location-url-with-javascript-without-page-r/5298684#5298684 */
                window.history.pushState("", document.title, window.location.pathname + window.location.search);
            }
        },
        watch: {
            modalEditShow(val) {
                if (val) {
                    $('.sidebar').width($('.sidebar').width());
                }
            },
            modalCreateShow(val) {
                if (val) {
                    $('.sidebar').width($('.sidebar').width());
                }
            }
        }
    };
</script>
