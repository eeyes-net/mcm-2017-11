<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>队伍编号</th>
                    <th>队员</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="team in teams">
                    <td>{{ team.id }}</td>
                    <td>{{ team.team_id }}</td>
                    <td>
                        <a href="javascript:" @click="$emit('showUser', user)" v-for="user in team.users">
                            <b-badge>{{ user.name }}</b-badge>
                        </a>
                    </td>
                    <td>{{ team.created_at }}</td>
                    <td>
                        <b-button variant="primary" @click="$emit('edit', team)">编辑</b-button>
                        <b-button variant="danger" @click="$emit('destory', team)">删除</b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'teams'
        ]
    }
</script>
