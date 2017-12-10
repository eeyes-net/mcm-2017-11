<template>
    <div>
        <b-form @submit.prevent="$emit('submit', form)">
            <b-form-group label="竞赛标题" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.title" required></b-form-input>
            </b-form-group>
            <b-form-group label="截止时间" horizontal :label-cols="3">
                <b-form-input type="text" v-model="form.expired_at" required></b-form-input>
            </b-form-group>
            <b-form-group label="发布时间" horizontal :label-cols="3" v-show="isEdit">
                <b-form-input type="text" v-model="form.created_at"></b-form-input>
            </b-form-group>
            <b-form-group label="状态" horizontal :label-cols="3">
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
            'match'
        ],
        data() {
            return {
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
        computed: {
            isEdit() {
                return this.type === 'edit';
            }
        },
        watch: {
            match(to, from) {
                this.form.id = to.id;
                this.form.title = to.title;
                this.form.expired_at = to.expired_at;
                this.form.created_at = to.created_at;
                this.form.status = to.status;
            }
        }
    }
</script>