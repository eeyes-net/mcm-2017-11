<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>队伍编号</th>
                    <th>队伍描述</th>
                    <th>发布时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="recruit in recruits">
                    <td>{{ recruit.id }}</td>
                    <td>{{ recruit.team_id }}</td>
                    <td><span :title="recruit.description">{{ recruit.description | clip }}</span></td>
                    <td>{{ recruit.created_at }}</td>
                    <td>
                        <b-button variant="primary" @click="$emit('edit', recruit)">编辑</b-button>
                        <b-button variant="danger" @click="$emit('destory', recruit)">删除</b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'recruits'
        ],
        filters: {
            clip(value) {
                return value.substr(0, 15) + (value.length > 15 ? '...' : '');
            }
        }
    }
</script>
