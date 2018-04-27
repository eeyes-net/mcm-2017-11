<script>
    export default {
        extends: VueChartJs.Bar,
        props: {
            label: {
                type: String,
                default: ''
            },
            data: {
                type: Object,
                default() {
                    return {};
                }
            },
            options: {
                type: Object,
                default() {
                    return {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                ticks: {
                                    autoSkip: false
                                }
                            }]
                        }
                    };
                }
            }
        },
        mounted() {
            this.render();
        },
        methods: {
            render() {
                this.renderChart(this.chartData, this.options);
            }
        },
        computed: {
            chartData() {
                return {
                    labels: _.keys(this.data),
                    datasets: [
                        {
                            label: this.label,
                            data: _.values(this.data),
                            datalabels: {
                                align: 'end',
                                anchor: 'end'
                            }
                        }
                    ]
                };
            },
        },
        watch: {
            data() {
                if (this.$data._chart) {
                    this.$data._chart.destroy();
                }
                this.render();
            }
        }
    };
</script>