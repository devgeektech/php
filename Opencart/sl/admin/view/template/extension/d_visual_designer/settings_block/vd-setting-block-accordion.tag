<vd-setting-block-accordion>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.accordion.entry_active_section')}</label>
        <div class="fg-setting">
            <input class="form-control" name="active_section" value="{setting.global.active_section}" onchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.accordion.entry_title')}</label>
        <div class="fg-setting">
            <input class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
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
</vd-setting-block-accordion>