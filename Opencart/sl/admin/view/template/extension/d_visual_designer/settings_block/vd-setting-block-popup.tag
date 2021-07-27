<vd-setting-block-popup>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.popup.entry_active_corner')}</label>
        <div class="fg-setting">
            <select class="form-control" name="active_corner" onChange={change}>
                <option value="{key}" selected={key == setting.global.active_corner} each={value, key in store.getOptions('blocks.popup.corners')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.popup.entry_click_trigger')}</label>
        <div class="fg-setting">
            <vd-switcher name="click_trigger" value="{setting.global.click_trigger}" evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="setting-selector" hide={!setting.global.click_trigger}>
        <label class="control-label">{store.getLocal('blocks.popup.entry_selector')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="selector" value="{setting.global.selector}" onchange={change}/>
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
</vd-setting-block-popup>