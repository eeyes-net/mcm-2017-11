<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>公告标题</th>
                    <th>发布时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts">
                    <td>{{ post.id }}</td>
                    <td>{{ post.title }}</td>
                    <td>{{ post.created_at }}</td>
                    <td>
                        <b-button variant="primary" @click="edit(post)">编辑</b-button>
                        <b-button variant="danger" @click="destory(post)">删除</b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'posts'
        ],
        methods: {
            edit(post) {
                this.$emit('edit', post);
            },
            destory(post) {
                this.$emit('destory', post);
            }
        }
    }
</script>
