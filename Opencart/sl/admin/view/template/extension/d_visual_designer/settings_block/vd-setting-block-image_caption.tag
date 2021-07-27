<vd-setting-block-image_caption>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeSummernote}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_image')}</label>
        <div class="fg-setting">
            <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title=""/></a>
            <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_alt')}</label>
        <div class="fg-setting">
            <input type="text" name="image_alt" class="form-control" value="{setting.global.image_alt}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="image_title" class="form-control" value="{setting.global.image_title}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.image_caption.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="size" hide={setting.global.size != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.image_caption.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" class="form-control pixels" value="{setting.global.width}" onChange={change}>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.image_caption.entry_height')}</label>
            <div class="fg-setting">
                <input type="text" name="height" class="form-control pixels" value="{setting.global.height}" onChange={change}>
            </div>
        </div>
    </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.image_caption.entry_onclick')}</label>
            <div class="fg-setting">
                <select class="form-control" name="onclick" onChange={change}>
                    <option value="{key}" selected={key == setting.global.onclick} each={value, key in store.getOptions('blocks.image_caption.actions')}>{value}</option>
                </select>
            </div>
        </div>
        <div id="link" hide={setting.global.onclick != 'link'}>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.image_caption.entry_link')}</label>
                <div class="fg-setting">
                    <input type="text" name="link" class="form-control" value="{setting.global.link}" onchange={change}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.image_caption.entry_link_target')}</label>
                <div class="fg-setting">
                    <select class="form-control" name="link_target" onChange={change}>
                        <option value="new" selected={'new' == setting.global.link_target}>{store.getLocal('blocks.image_caption.text_new_window')}</option>
                        <option value="current" selected={'current' == setting.global.link_target}>{store.getLocal('blocks.image_caption.text_current_window')}</option>
                    </select>
                </div>
            </div>
        </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_display_border')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_border" value="{setting.global.display_border}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_padding_text')}</label>
        <div class="fg-setting">
            <input type="text" name="padding_text" class="form-control pixels" value="{setting.global.padding_text}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_position_text')}</label>
        <div class="fg-setting">
            <select class="form-control" name="position_text" onChange={change}>
                <option value="{key}" selected={key == setting.global.position_text} each={value, key in store.getOptions('blocks.image_caption.positions')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_animate')}</label>
        <div class="fg-setting">
            <select class="form-control" name="animate" onChange={change}>
                <option value="{key}" selected={key == setting.global.animate} each={value, key in store.getOptions('blocks.image_caption.animates')}>{value}</option>
            </select>
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
</vd-setting-block-image_caption>