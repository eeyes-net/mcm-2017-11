<template>
    <div class="mb-2">
        <h2>重置数据库</h2>
        <layouts-error :errors="errors"></layouts-error>
        <b-alert :show="checking" variant="info">
            正在检查您是否有权限...
        </b-alert>
        <div v-show="authorized">
            <b-alert :show="true" variant="danger">
                <p>您应当充分了解重置数据库可能带来的后果！</p>
                <ul>
                    <li>全部 <strong>用户</strong> 将被删除</li>
                    <li>全部 <strong>公告</strong> 将被删除</li>
                    <li>已有 <strong>比赛</strong> 将被清空</li>
                    <li>所有 <strong>队伍</strong> 将被解散</li>
                    <li><strong>队伍编号</strong> 会重新从 1 开始编排</li>
                    <li>所有 <strong>招募</strong> 将被删除</li>
                    <li>您的管理员权限 <strong>不会</strong> 被删除</li>
                    <li>注意：竞赛快照 <strong>不会</strong> 被删除</li>
                    <li>您必须重新登录</li>
                </ul>
            </b-alert>
            <b-form @submit.prevent="reset_db">
                <b-form-group label="请输入“我确认重置数学建模网站数据库”">
                    <b-form-input type="text" v-model="confirm" class="text-danger"></b-form-input>
                </b-form-group>
                <b-button type="submit" variant="danger">重置数学建模网站数据库</b-button>
            </b-form>
            <div class="content p-3" v-show="result">
                <pre><code v-text="result"></code></pre>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
                checking: false,
                authorized: false,
                confirm: '',
                result: ''
            };
        },
        mounted() {
            this.checking = true;
            const _this = this;
            axios.get('/api/admin/reset_db/check')
                .then(response => {
                    _this.checking = false;
                    if (response.data) {
                        if (!response.data.login) {
                            window.location = response.data.redirect;
                        } else if (!response.data.permission) {
                            this.errors = [
                                '您没有重置数据库的权限'
                            ];
                        } else {
                            this.authorized = true;
                        }
                    }
                });
        },
        methods: {
            reset_db() {
                this.errors = [];
                if (this.confirm !== '我确认重置数学建模网站数据库') {
                    this.errors = [
                        '必须输入“我确认重置数学建模网站数据库”'
                    ];
                    return;
                }
                if (confirm('您确定要重置数据库吗？')) {
                    const _this = this;
                    axios.post('/api/admin/reset_db', {
                        confirm: this.confirm
                    })
                        .then(response => {
                            if (response.data && response.data.result) {
                                alert('重置成功');
                                console.log(response);
                                _this.result = response.data.output;
                            } else {
                                console.log(response);
                                _this.errors = response;
                                alert('重置失败');
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            _this.errors = error;
                            alert('重置失败');
                        });
                }
            }
        }
    };
</script>
