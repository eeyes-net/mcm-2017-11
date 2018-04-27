<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>快照文件名</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="matchSnapshot in matchSnapshots">
                    <td>{{ matchSnapshot.filename }}</td>
                    <td>
                        <b-button variant="primary" @click="$emit('download', matchSnapshot.path)">下载</b-button>
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
