<vd-setting-block-countdown>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
    <label class="control-label">{store.getLocal('blocks.countdown.entry_datetime')}</label>
        <div class="fg-setting">
            <vd-datetime-picker name="datetime" value={setting.global.datetime} format="YYYY-MM-DD HH:mm" placeholder="{store.getLocal('blocks.countdown.entry_datetime')}" evchange={change}/>

    </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeText}/>
            <formatted-message path="blocks.countdown.text_formatter"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown.entry_final_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'final_text'} value={setting.edit.final_text} evchange={changeText}/>
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
</vd-setting-block-countdown>