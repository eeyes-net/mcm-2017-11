<template>
    <div>
        <b-alert variant="danger" dismissible :show="show && formattedErrors.length > 0"><span v-for="error in formattedErrors">{{ error }}<br></span></b-alert>
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
            formattedErrors() {
                if (!this.show) {
                    return [];
                }
                let errors = this.errors;
                if (errors instanceof Array) {
                    // errors is response.data.data
                    return this.flattenArray(errors);
                } else if (errors instanceof Error) {
                    // errors is error
                    return this.errorToArray(errors);
                } else if (errors instanceof Object) {
                    if (errors.data instanceof Object && errors.request instanceof XMLHttpRequest) {
                        // errors is response
                        return this.responseToArray(errors);
                    } else {
                        // errors is response.data
                        return this.responseDataToArray(errors);
                    }
                } else {
                    return ['出现了一些未知问题，请联系管理员。'];
                }
            }
        },
        methods: {
            flattenArray(errors) {
                return _.flatten(_.toArray(errors));
            },
            errorToArray(error) {
                if (error.response) {
                    return this.responseToArray(error.response);
                } else {
                    return ['出现了一些问题，请联系管理员。错误信息：' + error.message];
                }
            },
            responseToArray(response) {
                if (response.data) {
                    return this.responseDataToArray(response.data);
                } else {
                    return ['出现了一些问题，请联系管理员。错误信息：' + response.status + ' ' + response.statusText];
                }
            },
            responseDataToArray(data) {
                if (data.errors) {
                    return this.flattenArray(data.errors);
                } else if (data.message) {
                    return [data.message];
                } else {
                    return _.concat(
                        ['出现了一些问题，请联系管理员。错误信息：'],
                        this.flattenArray(data)
                    );
                }
            }
        }
    };
</script>