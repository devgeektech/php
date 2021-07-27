<vd-block-line_chart>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <canvas width="649" height="324"></canvas>
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
        this.hex2rgb = function(hex) {
            r = hex.match(/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i);
            if (r) {
                    return r.slice(1,4).map(function(x) { return parseInt(x, 16); });
            }
            r = hex.match(/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i);
            if (r) {
                    return r.slice(1,4).map(function(x) { return 0x11 * parseInt(x, 16); });
            }
            return null;
        }
        this.initChart = function(){
            var setting = this.getState().setting
            var animates = this.getState().animates

            var ctx = $('canvas', this.root).get(0).getContext("2d");
            var chartData =  {labels: setting.global.x_values.split(';'), datasets: []}
            for (var key in setting.global.values) {
                if(setting.global.mode == 'line') {
                    var opacityColor = this.hex2rgb(setting.global.values[key].color)
                    opacityColor = 'rgba('+opacityColor.join(',')+', 0.1)'
                    chartData.datasets.push({
                        label: setting.global.values[key].label,
                        data: setting.global.values[key].value.split(','),
                        borderColor: setting.global.values[key].color,
                        backgroundColor: opacityColor
                    })
                } else {
                    chartData.datasets.push({
                        label: setting.global.values[key].label,
                        data: setting.global.values[key].value.split(','),
                        backgroundColor: setting.global.values[key].color
                    })
                }
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
</vd-block-line_chart>