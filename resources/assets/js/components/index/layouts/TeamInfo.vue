<template>
    <div>
        <b-table hover small responsive :items="userItems" :fields="userFields" class="mcm-team-info-table" :class="{'mcm-team-info-table-full-info': isFullInfo}"></b-table>
        <div v-if="isFullInfo">
            <h5 class="mcm-team-info-match-title">已报名比赛</h5>
            <b-table hover small responsive :items="matchItems" :fields="matchFields" class="mcm-team-info-table">
                <template slot="cancel_match" slot-scope="row">
                    <b-button variant="outline-danger" size="sm" @click.stop="cancelMatch(row)" v-if="row.item.canCancel">取消报名</b-button>
                    <b-button size="sm" disabled v-else>已截止</b-button>
                </template>
            </b-table>
        </div>
    </div>
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
                        users: [],
                        matches: []
                    };
                }
            }
        },
        methods: {
            cancelMatch(row) {
                this.$emit('cancel-match', row.item);
            }
        },
        computed: {
            positionTextMap() {
                return {
                    leader: '队长',
                    member: '队员'
                };
            },
            userFields() {
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
            userItems() {
                let items = [];
                const positionTextMap = this.positionTextMap;
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
                        item._rowVariant = 'info';
                    }
                    items.push(item);
                });
                return items;
            },
            statusTextMap() {
                return {
                    open: '已报名',
                    close: '已截止'
                };
            },
            matchFields() {
                return [
                    {key: 'title', label: '名称'},
                    {key: 'expired_at', label: '截止日期'},
                    {key: 'statusText', label: '状态'},
                    {key: 'cancel_match', label: '取消报名'}
                ];
            },
            matchItems() {
                let items = [];
                const statusTextMap = this.statusTextMap;
                _.forEach(this.team.matches, match => {
                    let item = {
                        id: match.id,
                        title: match.title,
                        expired_at: match.expired_at,
                        statusText: statusTextMap[match.status],
                        canCancel: match.status === 'open'
                    };
                    items.push(item);
                });
                return items;
            }
        }
    };
</script>
