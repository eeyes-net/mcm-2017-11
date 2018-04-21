<template>
    <b-modal :title="match.title" v-model="visible" :no-enforce-focus="true" ok-title="报名" cancel-title="取消" @ok="store">
        <layouts-error :errors="errors"></layouts-error>
        <b-form>
            <b-form-group label="选择队伍">
                <div class="d-sm-flex flex-row">
                    <b-form-select v-model="team_id" :options="teamOptions" class="mr-sm-2 mb-2 mb-sm-0"></b-form-select>
                    <a class="btn btn-outline-primary" href="/home#new_team" target="_blank">创建新队伍</a>
                </div>
            </b-form-group>
            <b-form-group label="队伍信息">
                <index-layouts-team-info :team="teamsById[team_id]"></index-layouts-team-info>
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
            match: {
                default() {
                    return {
                        id: 0,
                        title: ''
                    };
                }
            },
            visible: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                errors: [],
                teams: [],
                team_id: 0
            };
        },
        computed: {
            teamsById() {
                return _.keyBy(this.teams, 'id');
            },
            teamOptions () {
                let options = [];
                _.forEach(this.teams, team => {
                    options.push({
                        value: team.id,
                        text: '队伍编号：' + team.number + '（' + team.users.map(user => user.name).join() + '）'
                    });
                });
                return options;
            },
        },
        mounted() {
            this.getTeams();
        },
        methods: {
            getTeams() {
                axios.get('/api/team')
                    .then(response => {
                        if (response.data.data) {
                            this.teams = response.data.data;
                            if (this.teams) {
                                this.team_id = this.teams[0].id;
                            }
                        } else {
                            this.errors = response;
                        }
                    })
                    .catch(error => {
                        this.errors = error;
                    });
            },
            store(e) {
                e.preventDefault();
                this.errors = [];
                axios.post('/api/match/' + this.match.id + '/apply', {
                    team_id: this.team_id
                }).then(response => {
                    this.errors = [];
                    if (response.data.data) {
                        alert('报名成功');
                        this.$emit('ok', response.data);
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
