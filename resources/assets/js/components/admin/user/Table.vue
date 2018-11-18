<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NetID</th>
                    <th>姓名</th>
                    <th>学号</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td>{{ user.id }}</td>
                    <td>{{ user.username }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.stu_id }}</td>
                    <td>{{ user.created_at }}</td>
                    <td>
                        <b-button variant="primary" @click="$emit('edit', user)">编辑</b-button>
                        <!--<b-button variant="danger" @click="$emit('destory', user)">删除</b-button>-->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: [
            'users'
        ]
    }
</script>
