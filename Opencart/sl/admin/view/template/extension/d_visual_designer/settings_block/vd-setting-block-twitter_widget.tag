<vd-setting-block-twitter_widget>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_href')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="href" value="{setting.global.href}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_width')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="width" value="{setting.global.width}" onChange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_height')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="height" value="{setting.global.height}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_color_link')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_link'} value={setting.global.color_link} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_theme')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="theme" value={setting.global.theme} options={store.getOptions('blocks.twitter_widget.themes')} evchange={change}/>
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
</vd-setting-block-twitter_widget>