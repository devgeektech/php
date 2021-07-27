<vd-setting-block-custom_heading>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_text')}</label>
        <div class="fg-setting">
            <textarea class="form-control" name="text" onChange={change}>{setting.edit.text}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_tag')}</label>
        <div class="fg-setting">
            <select class="form-control" name="tag" onChange={change}>
                <option value="{value}" selected={value == setting.global.tag} each={value in store.getOptions('blocks.custom_heading.tags')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_font_family')}</label>
        <div class="fg-setting">
            <vd-font-picker name='font_family' value="{setting.global.font_family}" evchange={change}/>
            <vd-switcher name="underline" value="{setting.global.underline}" evchange={change} label={"<i class='fa fa-underline' aria-hidden='true'></i>"}/>
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change} label={"<i class='fa fa-bold' aria-hidden='true'></i>"}/>
            <vd-switcher name="italic" value="{setting.global.italic}" evchange={change} label={"<i class='fa fa-italic' aria-hidden='true'></i>"}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_line_height')}</label>
        <div class="fg-setting range">
            <vd-range name="line_height" value={setting.global.line_height} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.custom_heading.aligns')} evchange={change}/>
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
            if(!_.isUndefined(this.setting.edit[e.target.name])) {
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])) {
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-custom_heading>