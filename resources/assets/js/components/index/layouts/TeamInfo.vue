<template>
    <b-table striped hover :items="items" :fields="fields"></b-table>
</template>

<script>
    export default {
        props: {
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
                return [
                    {key: 'positionText', label: '身份', sortable: true},
                    {key: 'name', label: '姓名'},
                    {key: 'stu_id', label: '学号'}
                ];
            },
            items() {
                let items = [];
                let positionTextMap = this.positionTextMap;
                _.forEach(this.team.users, user => {
                    items.push({
                        positionText: positionTextMap[user.position],
                        name: user.name,
                        stu_id: user.stu_id
                    });
                });
                return items;
            }
        }
    };
</script>