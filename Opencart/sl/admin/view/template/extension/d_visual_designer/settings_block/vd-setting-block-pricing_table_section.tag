<vd-setting-block-pricing_table_section>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_subtitle')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="subtitle" value="{setting.global.subtitle}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_background')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'background'} value={setting.global.background} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_text'} value={setting.global.color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{key}" selected={key == setting.global.style} each={value, key in store.getOptions('blocks.pricing_table_section.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_currency')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="currency" value="{setting.global.currency}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_price')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="price" value="{setting.global.price}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_per')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="per" value="{setting.global.per}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_feautures')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="feautures" value="{setting.global.feautures}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_align_feauture')}</label>
        <div class="fg-setting">
            <select class="form-control" name="align_feauture" onChange={change}>
                <option value="{value}" selected={value == setting.global.align_feauture} each={value in store.getOptions('blocks.pricing_table_section.aligns')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_display_icon')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_icon" value="{setting.global.display_icon}" evchange={change}/>
        </div>
    </div>
    <div id="icon-setting" hide={!setting.global.display_icon}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_icon_library')}</label>
            <div class="fg-setting">
                <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_icon')}</label>
            <div class="fg-setting">
                <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_display_button')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_button" value="{setting.global.display_button}" evchange={change}/>
        </div>
    </div>
    <div id="button-setting" hide={!setting.global.display_button}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_text')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="button_text" value="{setting.global.button_text}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_link')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="button_link" value="{setting.global.button_link}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_style')}</label>
            <div class="fg-setting">
                <select class="form-control" name="button_style" onChange={change}>
                    <option value="{value}" selected={value == setting.global.button_style} each={value in store.getOptions('blocks.pricing_table_section.button_styles')}>{value}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_background')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_background'} value={setting.global.button_background} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_color_text'} value={setting.global.button_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_border_width')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control pixels" name="button_border_width" value="{setting.global.button_border_width}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_border_color'} value={setting.global.button_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_padding_top')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control pixels" name="button_padding_top" value="{setting.global.button_padding_top}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_padding_bottom')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control pixels" name="button_padding_bottom" value="{setting.global.button_padding_bottom}" onChange={change}/>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-pricing_table_section>