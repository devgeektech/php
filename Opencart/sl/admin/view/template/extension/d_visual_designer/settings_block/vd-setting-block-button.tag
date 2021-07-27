<vd-setting-block-button>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_text')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="text" value="{setting.global.text}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_action')}</label>
        <div class="fg-setting">
            <select class="form-control" name="action" onChange={change}>
                <option value="{key}" selected={key == setting.global.action} each={value, key in store.getOptions('blocks.button.actions')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="action-link" hide={setting.global.action != 'link'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_link')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_new_window')}</label>
            <div class="fg-setting">
                <vd-switcher name="new_window" value="{setting.global.new_window}" evchange={change}/>
            </div>
        </div>
    </div>
    <div id="action-buy" hide={setting.global.action != 'buy'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_product')}</label>
            <div class="fg-setting">
                <vd-autocomplete name="product_id" value={setting.global.product_id} url={store.getOptions('blocks.button.autocomplete')} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_quantity')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="quantity" value="{setting.global.quantity}" onChange={change}/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_text'} value={setting.global.color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="border_width" value={setting.global.border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'border_color'} value={setting.global.border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_radius')}</label>
        <div class="fg-setting">
            <vd-range name="border_radius" value={setting.global.border_radius} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_letter_spacing')}</label>
        <div class="fg-setting">
            <vd-range name="letter_spacing" value={setting.global.letter_spacing} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_font_family')}</label>
        <div class="fg-setting">
            <vd-font-picker name='font_family' value="{setting.global.font_family}" evchange={change}/>
            <vd-switcher name="underline" value="{setting.global.underline}" evchange={change} label={"<i class='fa fa-underline' aria-hidden='true'></i>"}/>
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change} label={"<i class='fa fa-bold' aria-hidden='true'></i>"}/>
            <vd-switcher name="italic" value="{setting.global.italic}" evchange={change} label={"<i class='fa fa-italic' aria-hidden='true'></i>"}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_display_icon')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_icon" value="{setting.global.display_icon}" evchange={change}/>
        </div>
    </div>
    <div id="icon-setting" hide={!setting.global.display_icon}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_library')}</label>
            <div class="fg-setting">
                <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon')}</label>
            <div class="fg-setting">
                <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'icon_color'} value={setting.global.icon_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_align')}</label>
            <div class="fg-setting">
                <vd-radio-btn-group name="icon_align" value={setting.global.icon_align} options={store.getOptions('blocks.button.icon_aligns')} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_only_hover')}</label>
            <div class="fg-setting">
                <vd-switcher name="display_icon_hover" value="{setting.global.display_icon_hover}" evchange={change}/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color_hover')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_hover'} value={setting.global.color_hover} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color_text_hover')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_text_hover'} value={setting.global.color_text_hover} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_color_hover')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'border_color_hover'} value={setting.global.border_color_hover} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_alignment')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="alignment" value={setting.global.alignment} options={store.getOptions('blocks.button.aligns')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_full_width')}</label>
        <div class="fg-setting">
            <vd-switcher name="full_width" value="{setting.global.full_width}" evchange={change}/>
        </div>
    </div>
    <div id="custom-width" hide={setting.global.full_width}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" value="{setting.global.width}" class="form-control pixels"  onChange={change}/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_height')}</label>
        <div class="fg-setting">
            <input type="text" name="height" value="{setting.global.height}" class="form-control pixels"  onChange={change}/>
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
</vd-setting-block-button>