<vd-setting-block-text_separater>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_title_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="title_align" value={setting.global.title_align} options={store.getOptions('blocks.text_separater.aligns')} evchange={change}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_font_size')}</label>
        <div class="fg-setting range">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_font_family')}</label>
        <div class="fg-setting">
            <vd-font-picker name='font_family' value="{setting.global.font_family}" evchange={change}/>
            <vd-switcher name="underline" value="{setting.global.underline}" evchange={change} label={"<i class='fa fa-underline' aria-hidden='true'></i>"}/>
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change} label={"<i class='fa fa-bold' aria-hidden='true'></i>"}/>
            <vd-switcher name="italic" value="{setting.global.italic}" evchange={change} label={"<i class='fa fa-italic' aria-hidden='true'></i>"}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_color_title')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_title'} value={setting.global.color_title} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="border_width" value={setting.global.border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_border_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{value}" selected={value == setting.global.style} each={value in store.getOptions('blocks.text_separater.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control percents" value="{setting.global.width}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.text_separater.aligns')} evchange={change}/>
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
</vd-setting-block-text_separater>