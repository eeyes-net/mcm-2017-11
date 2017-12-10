<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>竞赛标题</th>
                    <th>当前状态</th>
                    <th>截止时间</th>
                    <th>发布时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="match in matches">
                    <td>{{ match.id }}</td>
                    <td>{{ match.title }}</td>
                    <td>
                        <b-badge variant="secondary" v-if="match.status === 'close'">已截止</b-badge>
                        <b-badge variant="success" v-else-if="match.status === 'open'">开放报名</b-badge>
                        <b-badge variant="danger" v-else>{{ match.status }}</b-badge>
                    </td>
                    <td>{{ match.expired_at }}</td>
                    <td>{{ match.created_at }}</td>
                    <td>
                        <b-button variant="outline-info" @click="$emit('showTeams', match)">名单</b-button>
                        <b-button variant="primary" @click="$emit('edit', match)">编辑</b-button>
                        <b-button variant="danger" @click="$emit('destory', match)">删除</b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'matches'
        ]
    }
</script>
