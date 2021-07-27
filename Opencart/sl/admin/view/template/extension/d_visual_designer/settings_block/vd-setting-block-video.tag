<vd-setting-block-video>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.video.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.video.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control percents" value="{setting.global.width}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.video.entry_ratio')}</label>
        <div class="fg-setting">
            <select class="form-control" name="ratio" onChange={change}>
                <option value="{key}" selected={key == setting.global.ratio} each={value, key in store.getOptions('blocks.video.ratios')}>{value}</option>
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
</vd-setting-block-video>