<vd-setting-block-features>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeText} onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_image')}</label>
        <div class="fg-setting">
            <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title="" width="100px" height="100px"/></a>
            <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_alt')}</label>
        <div class="fg-setting">
            <input type="text" name="image_alt" class="form-control" value="{setting.global.image_alt}"  onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="image_title" class="form-control" value="{setting.global.image_title}"  onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option each={value, key in store.getOptions('blocks.features.sizes')} value="{key}" selected={setting.global.size == key}>{value}</option>
            </select>
        </div>
    </div>
    <div id="size" hide={setting.global.size != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.features.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" class="form-control pixels" value="{setting.global.width}"  onchange={change}>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.features.entry_height')}</label>
            <div class="fg-setting">
                <input type="text" name="height" class="form-control pixels" value="{setting.global.height}"  onchange={change}>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_image_position')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="image_position" value={setting.global.image_position} options={store.getOptions('blocks.features.image_positions')} evchange={change}/>
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
</vd-setting-block-features>