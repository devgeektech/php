<vd-block-round_chart>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <canvas></canvas>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            animates: {
                easeoutbounce: 'easeOutBounce',
                easeoutelastic: 'easeOutElastic',
                easeoutback: 'easeOutBack',
                easeinoutcubic: 'easeInOutCubic',
                easeinoutquint: 'easeInOutQuint',
                easeinoutquart: 'easeInOutQuart',
                easeinquad: 'easeInQuad',
                easeoutsine: 'easeOutSine',
            }
        })
        this.on('updated', function(){
            this.initChart()
        })
        this.on('mount', function(){
            this.initChart()
        })
        this.animates = {
            easeoutbounce: 'easeOutBounce',
            easeoutelastic: 'easeOutElastic',
            easeoutback: 'easeOutBack',
            easeinoutcubic: 'easeInOutCubic',
            easeinoutquint: 'easeInOutQuint',
            easeinoutquart: 'easeInOutQuart',
            easeinquad: 'easeInQuad',
            easeoutsine: 'easeOutSine',
        }
        this.initChart = function(){
            var setting = this.getState().setting
            var animates = this.getState().animates
            var ctx = $('canvas', this.root).get(0).getContext("2d");
            var chartData =  {labels: [], datasets: [{data:[], backgroundColor:[], label: setting.global.title}]}

            for (var key in setting.global.values) {
                chartData.datasets[0].data.push(setting.global.values[key].value)
                chartData.labels.push(setting.global.values[key].label)
                chartData.datasets[0].backgroundColor.push(setting.global.values[key].color)
            }

            var chart = new Chart(ctx, {
                type: setting.global.mode,
                data: chartData,
                options: {
                    animation:{
                        duration:3000,
                        easing: animates[setting.global.animate]
                    },
                    hover:{
                        intersect:false
                    },
                    elements:{
                        arc:{
                            borderWidth: setting.global.stroke_width
                        }
                    },
                    legend: {
                        display: setting.global.display_legend,
                        labels: {
                            display: setting.global.display_legend
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    </script>
</vd-block-round_chart>