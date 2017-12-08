<template>
    <div>
        <b-form @submit.prevent="submit">
            <b-form-group label="竞赛标题">
                <b-form-input type="text" v-model="form.title" required></b-form-input>
            </b-form-group>
            <b-form-group label="截止时间">
                <b-form-input type="text" v-model="form.expired_at" required></b-form-input>
            </b-form-group>
            <b-form-group label="发布时间" v-show="isEdit">
                <b-form-input type="text" v-model="form.created_at"></b-form-input>
            </b-form-group>
            <b-form-group label="状态">
                <b-form-select v-model="form.status" :options="options"></b-form-select>
            </b-form-group>
            <b-button type="submit" variant="primary" v-show="!isEdit">发布</b-button>
            <b-button type="submit" variant="primary" v-show="isEdit">更新</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: [
            'type',
            'matchId'
        ],
        data() {
            return {
                match: {},
                isEdit: false,
                form: {
                    id: 0,
                    title: '',
                    expired_at: '',
                    created_at: '',
                    status: 'close'
                },
                options: [
                    { value: 'close', text: '已截止' },
                    { value: 'open', text: '开放报名' }
                ]
            }
        },
        mounted() {
            this.isEdit = (this.type === 'edit');
        },
        watch: {
            matchId() {
                if (this.isEdit) {
                    this.getMatch(this.matchId);
                }
            }
        },
        methods: {
            submit() {
                this.$emit('submit', this.form);
            },
            getMatch(id) {
                axios.get('/api/admin/match/' + id)
                    .then(response => {
                        this.match = response.data;
                        this.form.id = this.match.id;
                        this.form.title = this.match.title;
                        this.form.expired_at = this.match.expired_at;
                        this.form.created_at = this.match.created_at;
                        this.form.status = this.match.status;
                    });
            }
        }
    }
</script>