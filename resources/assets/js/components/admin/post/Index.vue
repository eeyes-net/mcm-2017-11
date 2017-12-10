<template>
    <div>
        <h2>
            公告列表
            <span class="float-right">
                <b-button variant="primary" @click="create">发布新公告</b-button>
            </span>
        </h2>
        <admin-post-table :posts="postsData.data" @edit="edit" @destroy="destroy"></admin-post-table>
        <b-pagination :total-rows="postsData.total" v-model="postsData.current_page" :per-page="postsData.per_page" :limit="10" @change="changePage"></b-pagination>
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
            this.getPosts();
        },
        beforeRouteUpdate(to, from, next) {
            this.getPosts(to);
            next();
        },
        methods: {
            getPosts(route) {
                route = route || this.$router.currentRoute;
                let page = route.query.page || 1;
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
            destroy(post) {
                if (confirm('您确定要删除 #' + post.id + ' 《' + post.title + '》吗？')) {
                    axios.delete('/api/admin/post/' + post.id)
                        .then(response => {
                            this.getPosts();
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/post?page=' + page);
            }
        }
    }
</script>
