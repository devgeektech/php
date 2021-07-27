<vd-setting-block-google_plus_widget>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_href')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="href" value="{setting.global.href}"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_type')}</label>
        <div class="fg-setting">
            <select class="form-control" name="badge_type" onChange={change}>
                <option each={value, key in store.getOptions('blocks.google_plus_widget.types')} value="{key}" selected={setting.global.badge_type == key}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_width')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="width" value="{setting.global.width}" / onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_showcoverphoto')}</label>
        <div class="fg-setting">
            <vd-switcher name="showcoverphoto" value="{setting.global.showcoverphoto}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_showtagline')}</label>
        <div class="fg-setting">
            <vd-switcher name="showtagline" value="{setting.global.showtagline}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_layout')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="layout" value={setting.global.layout} options={store.getOptions('blocks.google_plus_widget.layouts')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_theme')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="theme" value={setting.global.theme} options={store.getOptions('blocks.google_plus_widget.themes')} evchange={change}/>
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
</vd-setting-block-google_plus_widget>