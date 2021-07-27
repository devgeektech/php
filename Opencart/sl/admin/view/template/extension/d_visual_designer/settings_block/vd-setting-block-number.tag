<vd-setting-block-number>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_number')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="number" value="{setting.global.number}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_thousand_separator')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="thousand_separator" value="{setting.global.thousand_separator}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_duration')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="duration" value="{setting.global.duration}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_font_bold')}</label>
        <div class="fg-setting">
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
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
</vd-setting-block-number>