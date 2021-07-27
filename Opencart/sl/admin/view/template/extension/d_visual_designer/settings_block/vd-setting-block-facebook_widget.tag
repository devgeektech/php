<vd-setting-block-facebook_widget>
    <div class="form-group">
    <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_href')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="href" value="{setting.global.href}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_height')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="height" value="{setting.global.height}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_width')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="width" value="{setting.global.width}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_tabs')}</label>
        <div class="fg-setting">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox" each={value, key in store.getOptions('blocks.facebook_widget.tabs')}>
                    <label>
                        <input type="checkbox" name="tabs" value="{key}" checked={_.indexOf(_.values(setting.global.tabs), key) != -1} onChange={changeCheckbox}/> {value}
                    </label>
                </div>
            </div>
            <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">{store.getLocal('blocks.facebook_widget.text_select_all')}</a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">{store.getLocal('blocks.facebook_widget.text_unselect_all')}</a>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_show_facepile')}</label>
        <div class="fg-setting">
            <vd-switcher name="show_facepile" value="{setting.global.show_facepile}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_small_header')}</label>
        <div class="fg-setting">
            <vd-switcher name="small_header" value="{setting.global.small_header}" evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeCheckbox(e) {
            if(e.target.checked) {
                if(!_.isEmpty(this.setting.global[e.target.name])){
                    var newIndex = _.max(_.map(_.keys(this.setting.global[e.target.name]), function(value){ return Number(value)}))+1;
                } else {
                    newIndex = 0
                }
                
                this.setting.global[e.target.name][newIndex] = e.target.value
            } else {
                this.setting.global[e.target.name] = _.pick(this.setting.global[e.target.name], function(value, key){ return value != e.target.value; });
            }

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
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
</vd-setting-block-facebook_widget>