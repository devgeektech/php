<vd-setting-block-conversion>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_type')}</label>
        <div class="fg-setting">
        <select class="form-control" name="type_conversion" onChange={change}>
            <option each={value, key in store.getOptions('blocks.conversion.types')} value="{key}" selected={setting.global.type_conversion == key}>{value}</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_selector_link')}</label>
        <div class="fg-setting">
            <input type="text" name="selector_link" class="form-control" value="{setting.edit.selector_link}" onChange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })

        if(_.isEmpty(this.setting.global.description_id)){

            this.setting.global.description_id = Math.random().toString(36).substring(2, 9)

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
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
</vd-setting-block-conversion>