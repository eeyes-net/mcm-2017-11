<template>
    <div>
        <b-form @submit.prevent="$emit('submit', form)">
            <b-form-group label="公告标题">
                <b-form-input type="text" v-model="form.title" required></b-form-input>
            </b-form-group>
            <b-form-group label="公告内容">
                <ckeditor v-model="form.content" :config="ckeditorConfig"></ckeditor>
            </b-form-group>
            <b-form-group label="发布时间" v-show="isEdit">
                <b-form-input type="text" v-model="form.created_at"></b-form-input>
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
            'post'
        ],
        data() {
            return {
                form: {
                    id: 0,
                    title: '',
                    content: '',
                    created_at: ''
                }
            }
        },
        computed: {
            isEdit() {
                return this.type === 'edit';
            },
            ckeditorConfig() {
                return require('../../../admin/ckeditor-config').default;
            }
        },
        watch: {
            post(to, from) {
                this.form.id = to.id;
                this.form.title = to.title;
                this.form.content = to.content;
                this.form.created_at = to.created_at;
            }
        }
    }
</script>