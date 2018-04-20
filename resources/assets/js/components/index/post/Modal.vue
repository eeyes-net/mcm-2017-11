<template>
    <b-modal :title="post.title" v-model="visible" size="lg" hide-footer :no-enforce-focus="true">
        <slot slot="modal-header">
            <div class="modal-title">
                <h5>{{ post.title }}</h5>
                <span class="post-date">{{ post.created_at.str }}</span>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </slot>
        <layouts-error :errors="errors"></layouts-error>
        <div class="post-content" v-html="post.content"></div>
    </b-modal>
</template>

<script>
    export default {
        model: {
            prop: 'visible',
            event: 'change'
        },
        props: {
            postId: {
                type: Number,
                default: 0
            },
            visible: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                errors: [],
                post: {
                    id: 0,
                    title: '',
                    content: '',
                    created_at: {
                        date: '',
                        timezone_type: 3,
                        timezone: '',
                        str: '',
                        diff: ''
                    }
                }
            };
        },
        methods: {
            initializePost() {
                this.post.title = '加载中...';
                this.post.content = '加载中...';
                this.post.created_at.str = '加载中...';
            },
            getPost(id) {
                this.errors = [];
                this.initializePost();
                axios.get('/api/post/' + id)
                    .then(response => {
                        this.errors = [];
                        this.$emit('loaded', response);
                        if (response.data.data) {
                            this.errors = [];
                            this.$emit('ok', response.data.data);
                            this.post = response.data.data;
                        } else {
                            this.errors = response;
                            this.$emit('error', response);
                        }
                    })
                    .catch(error => {
                        this.$emit('error', error);
                        this.errors = error;
                    });
            }
        },
        watch: {
            postId(newVal, oldVal) {
                if (newVal !== oldVal) {
                    this.getPost(newVal);
                }
            },
            visible(newVal, oldVal) {
                this.$emit('change', newVal);
            }
        }
    };
</script>
