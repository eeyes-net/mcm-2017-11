<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>竞赛标题</th>
                    <th>截止时间</th>
                    <th>发布时间</th>
                    <th>快照时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="matchSnapshot in matchSnapshots">
                    <td>{{ matchSnapshot.id }}</td>
                    <td>{{ matchSnapshot.title }}</td>
                    <td>{{ matchSnapshot.expired_at }}</td>
                    <td>{{ matchSnapshot.match_created_at }}</td>
                    <td>{{ matchSnapshot.created_at }}</td>
                    <td>
                        <b-button variant="primary" @click="$emit('export', matchSnapshot)">导出</b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'matchSnapshots'
        ]
    }
</script>
