<template>
    <div>
        <b-alert variant="danger" dismissible :show="show && formattedError.length > 0"><span v-for="error in formattedError">{{ error }}<br></span></b-alert>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                default: true
            },
            errors: {
                default: function () {
                    return [];
                }
            }
        },
        computed: {
            formattedError() {
                if (!this.show) {
                    return [];
                }
                let errors = this.errors;
                if (errors instanceof Array) {
                    // errors is response.data.data
                    return _.flatten(_.toArray(errors));
                } else if (errors instanceof Error) {
                    // errors is error
                    if (errors.message === 'Request failed with status code 422' && errors.response) {
                        if (errors.response.data && errors.response.data.errors) {
                            return _.flatten(_.toArray(errors.response.data.errors));
                        } else {
                            return ['出现了一些问题，请联系管理员。错误信息：' + errors.message + ' (' + errors.response.statusText + ')'];
                        }
                    } else {
                        if (errors.response) {
                            return ['出现了一些问题，请联系管理员。错误信息：' + errors.message + ' (' + errors.response.statusText + ')'];
                        } else {
                            return ['出现了一些问题，请联系管理员。错误信息：' + errors.message];
                        }
                    }
                } else if (errors instanceof Object) {
                    if (errors.data instanceof Object) {
                        // errors is response
                        if (errors.data.message === 'Request failed with status code 422') {
                            return _.flatten(_.toArray(errors.data.errors));
                        } else {
                            return ['出现了一些问题，请联系管理员。错误信息：' + errors.message];
                        }
                    } else if (errors.message === 'Request failed with status code 422') {
                        // errors is response.data
                        return _.flatten(_.toArray(errors.response.data.errors));
                    } else {
                        return ['出现了一些未知问题，请联系管理员。'];
                    }
                } else {
                    return ['出现了一些未知问题，请联系管理员。'];
                }
            }
        }
    };
</script>