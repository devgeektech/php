<vd-setting-block-message_box>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{key}" selected={key == setting.global.style} each={value, key in store.getOptions('blocks.message_box.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_share')}</label>
        <div class="fg-setting">
            <select class="form-control" name="share" onChange={change}>
                <option value="{key}" selected={key == setting.global.share} each={value, key in store.getOptions('blocks.message_box.shares')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'border_color'} value={setting.global.border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_icon')}</label>
        <div class="fg-setting">
            <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeSummernote}/>
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
        changeSummernote(name, value){
            this.setting.global[name] = value
            this.setting.edit[name] = value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-message_box>