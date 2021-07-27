<vd-setting-block-code>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.code.entry_code')}</label>
        <div class="fg-setting">
            <textarea name="code" class="form-control" onchange={change}>{setting.edit.code}</textarea>
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
            this.setting.edit[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-code>