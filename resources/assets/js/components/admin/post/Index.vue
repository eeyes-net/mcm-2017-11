<template>
    <div>
        <h2>
            公告列表
            <span class="float-right">
                <b-button variant="primary" @click="create">发布新公告</b-button>
            </span>
        </h2>
        <admin-post-table :posts="postsData.data" @edit="edit" @destory="destory"></admin-post-table>
        <b-pagination :total-rows="postsData.total" v-model="postsData.current_page" :per-page="postsData.per_page" :limit="paginationLimit" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                postsData: {}
            };
        },
        mounted() {
            let page = this.$router.currentRoute.query.page || 1;
            this.getPosts(page);
        },
        beforeRouteUpdate(to, from, next) {
            let page = to.query.page || 1;
            this.getPosts(page);
            next();
        },
        computed: {
            paginationLimit() {
                return 10;
            }
        },
        methods: {
            getPosts(page = 1) {
                axios.get('/api/admin/post', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.postsData = response.data;
                });
            },
            create() {
                this.$router.push('/admin/post/create');
            },
            edit(post) {
                this.$router.push('/admin/post/' + post.id + '/edit');
            },
            destory(post) {
                if (confirm('您确定要删除 #' + post.id + ' 《' + post.title + '》吗？')) {
                    axios.delete('/api/admin/post/' + post.id)
                        .then(response => {
                            this.getPosts(this.postsData.current_page);
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/post?page=' + page);
            }
        }
    }
</script>
