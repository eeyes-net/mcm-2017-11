<template>
    <div>
        <b-form @submit.prevent="submit">
            <b-form-group label="公告标题">
                <b-form-input type="text" v-model="form.title" required></b-form-input>
            </b-form-group>
            <b-form-group label="公告内容">
                <ckeditor v-model="form.content"></ckeditor>
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
            'postId'
        ],
        data() {
            return {
                post: {},
                isEdit: false,
                form: {
                    id: 0,
                    title: '',
                    content: '',
                    created_at: ''
                }
            }
        },
        mounted() {
            this.isEdit = (this.type === 'edit');
        },
        watch: {
            postId() {
                if (this.isEdit) {
                    this.getPost(this.postId);
                }
            }
        },
        methods: {
            submit() {
                this.$emit('submit', this.form);
            },
            getPost(id) {
                setTimeout(() => { // TODO: hack for vue-ckeditor2 issue https://github.com/dangvanthanh/vue-ckeditor2/issues/39 , may be removed if issue fixed.
                axios.get('/api/admin/post/' + id)
                    .then(response => {
                        this.post = response.data;
                        this.form.id = this.post.id;
                        this.form.title = this.post.title;
                        this.form.content = this.post.content;
                        this.form.created_at = this.post.created_at;
                    });
                }, 1000);
            }
        }
    }
</script>