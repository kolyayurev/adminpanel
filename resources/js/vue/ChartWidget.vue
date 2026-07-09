<template>
    <div class="chart-widget" v-cloak>
        <canvas ref="canvas"></canvas>
    </div>
</template>

<script>
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

export default {
    name: 'v-chart-widget',
    props: {
        widgetSlug: String,
    },
    data() {
        return {
            chart: null,
        }
    },
    mounted() {
        this.fetch()
        window.addEventListener('adminpanel:widgets:refresh', this.onRefresh)
    },
    beforeUnmount() {
        window.removeEventListener('adminpanel:widgets:refresh', this.onRefresh)
        if (this.chart) this.chart.destroy()
    },
    methods: {
        onRefresh(event) {
            this.fetch(event.detail || {})
        },
        fetch(params = {}) {
            axios.get(window.route('adminpanel.widgets.data', { widget: this.widgetSlug }), { params })
                .then((r) => this.render(r.data))
                .catch(() => { toastr.error(lang.get('common.whoopsie') || 'Ошибка') })
        },
        render(payload) {
            // options передаются как есть — весь набор возможностей Chart.js (scales,
            // plugins, legend, animation и т.д.) управляется из PHP без интерпретации пакетом.
            const config = {
                type: payload.type || 'line',
                data: { labels: payload.labels || [], datasets: payload.datasets || [] },
                options: payload.options || {},
            }

            if (this.chart) {
                this.chart.config.type = config.type
                this.chart.data = config.data
                this.chart.options = config.options
                this.chart.update()

                return
            }

            this.chart = new Chart(this.$refs.canvas, config)
        },
    },
}
</script>
