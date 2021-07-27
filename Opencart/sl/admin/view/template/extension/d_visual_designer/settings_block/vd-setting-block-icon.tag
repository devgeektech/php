<vd-setting-block-icon>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_icon')}</label>
        <div class="fg-setting">
            <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_background_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="background_style" onChange={change}>
                <option value="{key}" selected={key == setting.global.background_style} each={value, key in store.getOptions('blocks.icon.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_background_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'background_color'} value={setting.global.background_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.icon.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.icon.aligns')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_adaptive_design')}</label>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.icon.column_device')}</td>
                    <td>{store.getLocal('blocks.icon.column_align')}</td>
                    <td>{store.getLocal('blocks.icon.column_size')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.icon.text_tablet')}</td>
                    <td class="text-center">
                        <select class="form-control" name="align_tablet" onChange={change}>
                            <option value="" selected={'' == setting.global.align_tablet}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.align_tablet} each={value, key in store.getOptions('blocks.icon.aligns')}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="size_tablet" onChange={change}>
                            <option value="" selected={'' == setting.global.size_tablet}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.size_tablet} each={value, key in store.getOptions('blocks.icon.sizes')}>{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.icon.text_phone')}</td>
                    <td class="text-center">
                        <select class="form-control" name="align_phone" onChange={change}>
                            <option value="" selected={'' == setting.global.align_phone}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.align_phone} each={value, key in store.getOptions('blocks.icon.aligns')}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="size_phone" onChange={change}>
                            <option value="" selected={'' == setting.global.size_phone}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.size_phone} each={value, key in store.getOptions('blocks.icon.sizes')}>{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}"  onChange={change} />
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
</vd-setting-block-icon>