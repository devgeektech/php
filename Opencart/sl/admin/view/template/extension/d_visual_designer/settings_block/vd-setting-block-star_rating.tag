<vd-setting-block-star_rating>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.star_rating.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.star_rating.entry_rating')}</label>
        <div class="fg-setting">
            <select class="form-control" name="rating" onChange={change}>
                <option value="{value}" selected={value == setting.global.rating} each={value in store.getOptions('blocks.star_rating.ratings')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.star_rating.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="star_size" onChange={change}>
                <option value="{key}" selected={key == setting.global.star_size} each={value, key in store.getOptions('blocks.star_rating.sizes')}>{value}</option>
            </select>
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
</vd-setting-block-star_rating>