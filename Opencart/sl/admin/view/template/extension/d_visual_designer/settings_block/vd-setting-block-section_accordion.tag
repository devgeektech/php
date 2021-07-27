<vd-setting-block-section_accordion>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.section_accordion.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onchange={change}>
        </div>
    </div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }
    </script>
</vd-setting-block-section_accordion>