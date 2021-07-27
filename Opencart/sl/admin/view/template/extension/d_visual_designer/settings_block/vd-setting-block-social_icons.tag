<vd-setting-block-social_icons>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_type')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="icon_type" value={setting.global.icon_type} options={store.getOptions('blocks.social_icons.types')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_margin_beetwen')}</label>
        <div class="fg-setting range">
            <vd-range name="margin_beetwen" value={setting.global.margin_beetwen} evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.social_icons.aligns')} evchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_adaptive_design')}</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.social_icons.column_device')}</td>
                    <td>{store.getLocal('blocks.social_icons.column_margin_beetwen')}</td>
                    <td>{store.getLocal('blocks.social_icons.column_align')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.column.text_tablet')}</td>
                    <td>
                        <input type="text" name="margin_beetwen_tablet" class="form-control pixels" value={setting.global.margin_beetwen_tablet} onchange={change}/>
                    </td>
                    <td>
                        <select class="form-control" name="align_tablet" onChange={change}>
                            <option value="" selected={setting.global.align_tablet == ''}>{store.getLocal('blocks.image.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.social_icons.aligns')} value="{key}" selected={setting.global.align_tablet == key} selected="selected">{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.column.text_phone')}</td>
                    <td>
                        <input type="text" name="margin_beetwen_phone" class="form-control pixels" value={setting.global.margin_beetwen_phone} onchange={change}/>
                    </td>
                    <td>
                        <select class="form-control" name="align_phone" onChange={change}>
                            <option value="" selected={setting.global.align_phone == ''}>{store.getLocal('blocks.image.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.social_icons.aligns')} value="{key}" selected={setting.global.align_phone == key} selected="selected">{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <vd-collapse tabs={setting.global.icons}>
        <vd-collapse-item each={tab_setting, tab in opts.tabs} title="{store.getLocal('blocks.social_icons.text_'+tab)}">
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_icon')}</label>
                <div class="fg-setting">
                    <vd-icon-picker name="{tab}.icon" value={tab_setting.icon} evchange={top.changeIcon} library={top.setting.global.library}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_link')}</label>
                <div class="fg-setting">
                    <input type="text" class="form-control" name="{tab}.link" value={tab_setting.link} onChange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_size')}</label>
                <div class="fg-setting range">
                    <vd-range name="{tab}.size" value={tab_setting.size} evchange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group" if={top.setting.global.icon_type != 'text'}>
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_background')}</label>
                <div class="fg-setting">
                    <vd-color-picker name="{tab}.background" value={tab_setting.background} evchange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_color')}</label>
                <div class="fg-setting">
                    <vd-color-picker name="{tab}.color" value={tab_setting.color} evchange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_sort_order')}</label>
                <div class="fg-setting">
                    <input type="text" class="form-control" name="{tab}.sort_order" value={tab_setting.sort_order} onChange={top.changeIcon}/>
                </div>
            </div>
        </vd-collapse-item>
    </vd-collapse>
    <script>
        this.mixin({store:d_visual_designer})
        this.active = ''
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeIcon(e) {
            var names = e.target.name.split('.')
            this.setting.global.icons[names[0]][names[1]] = e.target.value
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
</vd-setting-block-social_icons>