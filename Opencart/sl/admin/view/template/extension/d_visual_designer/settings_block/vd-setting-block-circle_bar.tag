<vd-setting-block-circle_bar>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_units')}</label>
        <div class="fg-setting">
            <input type="text" name="units" class="form-control" value="{setting.global.units}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_bar_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'bar_color'} value={setting.global.bar_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_track_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'track_color'} value={setting.global.track_color} evchange={change}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_line_width')}</label>
        <div class="fg-setting">
            <vd-range name="line_width" value={setting.global.line_width} evchange={change}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_size')}</label>
        <div class="fg-setting">
            <vd-range name="size" value={setting.global.size} evchange={change} max='200'/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-circle_bar>