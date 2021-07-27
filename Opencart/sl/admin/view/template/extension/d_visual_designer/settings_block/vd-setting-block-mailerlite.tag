<vd-setting-block-mailerlite>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_api')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="api" value="{setting.global.api}" onchange={change}/>
        </div>
        <br/>
        <div class="alert alert-info"> <formatted-message path="blocks.mailerlite.help_api"/></div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_lists')}</label>
        <div class="fg-setting">
            <div class="input-group">
                <select class="form-control" name="list_id" onChange={change}>
                    <option value="{key}" selected={key == setting.global.list_id} each={value, key in setting.edit.lists}>{value}</option>
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="button_load_lists" onclick={updateList}>{store.getLocal('blocks.mailerlite.button_refresh')}</button>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_display_firstname')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_firstname" value="{setting.global.display_firstname}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_display_lastname')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_lastname" value="{setting.global.display_lastname}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_inline')}</label>
        <div class="fg-setting">
            <vd-switcher name="inline" value="{setting.global.inline}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_final_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'final_text'} value={setting.edit.final_text} evchange={changeText}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_background_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_background_color'} value={setting.global.input_background_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_color_text'} value={setting.global.input_color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_border_color'} value={setting.global.input_border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_focus_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_focus_border_color'} value={setting.global.input_focus_border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="input_border_width" value={setting.global.input_border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_border_radius')}</label>
        <div class="fg-setting">
            <vd-range name="input_border_radius" value={setting.global.input_border_radius} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="input_font_size" value={setting.global.input_font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_width')}</label>
        <div class="fg-setting">
            <input type="text" name="input_width" value="{setting.global.input_width}" class="form-control pixels-procent" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_height')}</label>
        <div class="fg-setting">
            <input type="text" name="input_height" value="{setting.global.input_height}" class="form-control pixels-procent" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_title')}</label>
        <div class="fg-setting">
            <input type="text" name="button_title" value="{setting.global.button_title}" class="form-control" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="button_style" onChange={change}>
                <option value="{value}" selected={value == setting.global.button_style} each={value in store.getOptions('blocks.mailerlite.button_styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="vd-custom-style-button" hide={setting.global.button_style != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_background_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_background_color'} value={setting.global.button_background_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_color_text'} value={setting.global.button_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_hover_background_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_background_color'} value={setting.global.button_hover_background_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_hover_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_color_text'} value={setting.global.button_hover_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_border_color'} value={setting.global.button_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_hover_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_border_color'} value={setting.global.button_hover_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_border_width')}</label>
            <div class="fg-setting range">
                <vd-range name="button_border_width" value={setting.global.button_border_width} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_border_radius')}</label>
            <div class="fg-setting">
                <vd-range name="button_border_radius" value={setting.global.button_border_radius} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_font_size')}</label>
            <div class="fg-setting">
                <vd-range name="button_font_size" value={setting.global.button_font_size} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_width')}</label>
            <div class="fg-setting">
                <input type="text" name="button_width" value="{setting.global.button_width}" class="form-control pixels" onchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_height')}</label>
            <div class="fg-setting">
                <input type="text" name="button_height" value="{setting.global.button_height}" class="form-control pixels" onchange={change}/>
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
        updateList(e) {
            $.ajax({
                url: 'index.php?route=extension/d_visual_designer_module/mailerlite/getList&'+this.store.getOptions('blocks.mailerlite.url_token'),
                type: "POST",
                dataType: "json",
                context: this,
                data: 'api=' + this.setting.global.api,
                success: function(json) {
                    if (json['success']) {
                        this.setting.edit.lists = json.lists

                        if(!_.isEmpty(json.lists)){
                            this.setting.global.list_id = _.keys(json.lists)[0]
                        }

                        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
                        this.update()
                    }
                }
            });
            }.bind(this)
    </script>
</vd-setting-block-mailerlite>