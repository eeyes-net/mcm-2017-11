<template>
    <b-modal title="队伍招募" v-model="visible" :no-enforce-focus="true" ok-title="发布招募" cancel-title="取消" @ok="store">
        <layouts-error :errors="errors"></layouts-error>
        <b-form>
            <b-form-group label="选择招募类型">
                <b-form-checkbox-group v-model="form.tags" :options="recruitTagOptions"></b-form-checkbox-group>
            </b-form-group>
            <b-form-group label="选择队伍">
                <div class="d-sm-flex flex-row">
                    <b-form-select v-model="form.team_id" :options="teamOptions" class="mr-sm-2 mb-2 mb-sm-0"></b-form-select>
                    <b-btn variant="outline-primary">创建新队伍</b-btn>
                </div>
            </b-form-group>
            <b-form-group label="队伍信息">
                <index-layouts-team-info :team="teamsById[form.team_id]"></index-layouts-team-info>
            </b-form-group>
            <b-form-group label="队伍描述">
                <b-textarea v-model="form.description" :rows="3" placeholder="请添加您的队伍描述，不超过48个字"></b-textarea>
            </b-form-group>
            <b-form-group label="联系方式">
                <b-input v-model="form.contact" placeholder="请留下您的联系方式"></b-input>
            </b-form-group>
        </b-form>
    </b-modal>
</template>

<script>
    export default {
        model: {
            prop: 'visible',
            event: 'change'
        },
        props: {
            visible: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                errors: [],
                recruitTagOptions: [],
                teams: [],
                form: {
                    tags: [],
                    team_id: 0,
                    description: '',
                    contact: ''
                }
            };
        },
        computed: {
            teamUsersCountLimit() {
                return 3;
            },
            teamsById() {
                return _.keyBy(this.teams, 'id');
            },
            teamOptions() {
                let options = [];
                _.forEach(this.teams, team => {
                    let option = {
                        value: team.id,
                        text: '队伍编号：' + team.number + '（' + team.users.map(user => user.name).join() + '）'
                    };
                    if (team.users.length >= this.teamUsersCountLimit) {
                        option.text += '已满' + this.teamUsersCountLimit + '人';
                        option.disabled = true;
                    }
                    options.push(option);
                });
                return options;
            }
        },
        mounted() {
            this.getRecruitTags();
            this.getTeams();
        },
        methods: {
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
            getTeams() {
                this.errors = [];
                axios.get('/api/team').then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        this.teams = response.data.data;
                        if (this.teams) {
                            this.form.team_id = this.teams[0].id;
                        }
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
            },
            store(e) {
                e.preventDefault();
                this.errors = [];
                let form = this.form;
                axios.post('/api/recruit', {
                    team_id: form.team_id,
                    tags: form.tags,
                    description: form.description,
                    contact: form.contact
                }).then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        alert('发布成功');
                        this.$emit('ok', response.data.data);
                        this.visible = false;
                    } else {
                        this.errors = response;
                    }
                }).catch(error => {
                    this.errors = error;
                });
                return true;
            }
        },
        watch: {
            visible(newVal, oldVal) {
                this.$emit('change', newVal);
            }
        }
    };
</script>
