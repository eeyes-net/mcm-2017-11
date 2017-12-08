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
                    <td>{{ match.status | statusText }}</td>
                    <td>{{ match.expired_at }}</td>
                    <td>{{ match.created_at }}</td>
                    <td>
                        <b-button variant="outline-info" @click="exportList(match)">导出名单</b-button>
                        <b-button variant="primary" @click="edit(match)">编辑</b-button>
                        <b-button variant="danger" @click="destory(match)">删除</b-button>
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
        ],
        filters: {
            statusText(value) {
                let text = '未知状态';
                switch (value) {
                    case 'close':
                        text = '已截止';
                        break;
                    case 'open':
                        text = '开放报名';
                        break;
                }
                return text;
            }
        },
        methods: {
            exportList(match) {
                this.$emit('exportList', match);
            },
            edit(match) {
                this.$emit('edit', match);
            },
            destory(match) {
                this.$emit('destory', match);
            }
        }
    }
</script>
