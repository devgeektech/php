<vd-setting-block-pricing_table>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table.entry_width_between')}</label>
        <div class="fg-setting">
            <input type="text" name="width_between" value="{setting.global.width_between}" class="form-control pixels" onchange={change}/>
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
</vd-setting-block-pricing_table>