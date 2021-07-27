<vd-setting-block-facebook_like>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_like.entry_type')}</label>
        <div class="fg-setting">
            <select class="form-control" name="type_button" onChange={change}>
                <option value="{key}" selected={key == setting.global.type_button} each={value, key in store.getOptions('blocks.facebook_like.types')}>{value}</option>
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
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-facebook_like>