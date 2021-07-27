<vd-block-circle_bar>
    <div class="h2" if={getState().setting.title}>{getState().setting.global.title}</div>
    <div class="pie" data-percent="{getState().setting.global.value}">
        <span class="pie-value" if={getState().setting.global.label_value}>{getState().setting.global.label_value} {getState().setting.global.units}</span>
        <span class="pie-value" if={!getState().setting.global.label_value}>{getState().setting.global.value} {getState().setting.global.units}</span>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            pieChart: null
        })
        this.on('update', function(){
            this.initCircleBar()
        })
        this.on('mount', function(){
            this.initCircleBar()
        })
        this.initCircleBar = function(){
            var pieChart = this.getState().pieChart
            var block_setting = this.getState().setting
            var setting = {};
            if(block_setting.global.bar_color) {
                setting['barColor'] = block_setting.global.bar_color;
            }
            if(block_setting.global.track_color) {
                setting['trackColor'] = block_setting.global.track_color;
            }
            if(block_setting.global.line_width) {
                setting['lineWidth'] = block_setting.global.line_width.replace('px', '');
            }
            if(block_setting.global.size) {
                setting['size'] = block_setting.global.size.replace('px', '');
            }
            if(!_.isNull(pieChart)){
                pieChart.removeData('pieChart')
                $('.pie canvas', this.root).remove()
            }
            this.setState({pieChart: $('.pie', this.root).pieChart(setting)})
        }
    </script>
</vd-block-circle_bar>