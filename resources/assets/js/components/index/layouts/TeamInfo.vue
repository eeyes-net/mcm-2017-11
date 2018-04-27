<template>
    <b-table hover small responsive :items="items" :fields="fields" class="mcm-team-info-table" :class="{'mcm-team-info-table-full-info': isFullInfo}"></b-table>
</template>

<script>
    export default {
        props: {
            isFullInfo: {
                type: Boolean,
                default: false
            },
            team: {
                default() {
                    return {
                        users: []
                    };
                }
            }
        },
        computed: {
            positionTextMap() {
                return {
                    leader: '队长',
                    member: '队员'
                };
            },
            fields() {
                let fields = [
                    {key: 'positionText', label: '身份', sortable: true},
                    {key: 'name', label: '姓名'},
                    {key: 'stu_id', label: '学号'}
                ];
                if (this.isFullInfo) {
                    fields = _.concat(fields, [
                        {key: 'class', label: '班级'},
                        {key: 'department', label: '学院'},
                        {key: 'contact', label: '联系电话'},
                        {key: 'email', label: '邮箱'}
                    ]);
                }
                return fields;
            },
            items() {
                let items = [];
                let positionTextMap = this.positionTextMap;
                _.forEach(this.team.users, user => {
                    let item = {
                        positionText: positionTextMap[user.position],
                        name: user.name,
                        stu_id: user.stu_id,
                        class: user.class,
                        department: user.department,
                        contact: user.contact,
                        email: user.email
                    };
                    if (user.position === 'leader') {
                        item._rowVariant = 'secondary';
                    }
                    items.push(item);
                });
                return items;
            }
        }
    };
</script>