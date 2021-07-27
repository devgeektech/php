<vd-setting-block-testimonial>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.testimonial.entry_image')}</label>
        <div class="fg-setting">
            <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title="" width="100px" height="100px"/></a>
            <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.testimonial.entry_name')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="name" value="{setting.global.name}" onChange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.testimonial.entry_comment')}</label>
        <div class="fg-setting">
            <vd-summernote name={'comment'} value={setting.edit.comment} evchange={changeText} onchange={change}/>
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
</vd-setting-block-testimonial>