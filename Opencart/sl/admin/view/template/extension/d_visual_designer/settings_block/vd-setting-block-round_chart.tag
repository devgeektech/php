<vd-setting-block-round_chart>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_values')}</label>
        <div class="fg-setting">
                        <table id="bar-values" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{store.getLocal('blocks.round_chart.entry_label')}</td>
                        <td class="text-left">{store.getLocal('blocks.round_chart.entry_value')}</td>
                        <td class="text-left">{store.getLocal('blocks.round_chart.entry_color')}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                        <tr each={value, key in setting.global.values}>
                            <td class="text-left">
                                <input type="text" class="form-control" name="{key}.label"  value="{value.label}" onchange={changeValue} />
                            </td>
                            <td class="text-left">
                                <input type="text" class="form-control" name="{key}.value"  value="{value.value}" onchange={changeValue} />
                            </td>
                            <td class="text-left">
                                <vd-color-picker name={key+'.color'} value={value.color} evchange={changeValue}/>
                            </td>
                            <td class="text-left"><button type="button" onclick={removeValue} data-toggle="tooltip" title="{store.getLocal('blocks.round_chart.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left"><button type="button" onclick={addValue} data-toggle="tooltip" title="{store.getLocal('blocks.round_chart.button_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_modes')}</label>
        <div class="fg-setting">
            <select class="form-control" name="mode" onChange={change}>
                <option value="{key}" selected={key == setting.global.mode} each={value, key in store.getOptions('blocks.round_chart.modes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_gap')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="stroke_width" value="{setting.global.stroke_width}"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_display_legend')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_legend" value="{setting.global.display_legend}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_animate')}</label>
        <div class="fg-setting">
            <select class="form-control" name="animate" onChange={change}>
                <option value="{key}" selected={key == setting.global.animate} each={value, key in store.getOptions('blocks.round_chart.animates')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addValue(e) {
            var value_setting = {label: '', value: '', color: ''};

            if(_.isEmpty(this.setting.global.values)) {
                this.setting.global.values = {0: value_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.values), function(value){return Number(value)})) + 1
                this.setting.global.values[new_index] = value_setting
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeValue(e) {
            this.setting.global.values = _.pick(this.setting.global.values, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeValue(e) {
            recompose(this.setting.global.values, e.target.name, e.target.value)
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        function recompose(obj,is, value) {
            if (typeof is == 'string')
                return recompose(obj,is.split('.'), value);
            else if (is.length==1 && value!==undefined)
                return obj[is[0]] = value;
            else if (is.length==0)
                return obj;
            else
                return recompose(obj[is[0]],is.slice(1), value);
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-round_chart>