<vd-setting-block-module>
    <div class="form-group">
    <label class="col-sm-2 control-label" for="input-row">{store.getLocal('blocks.code.entry_module')}</label>
    <div class="fg-setting">
        <select name="code" class="form-control" onchange={change}>     
            <optgroup each={extension in store.getOptions('blocks.module.extensions')} label="{extension.name}" >
                <option if={!_.isEmpty(extension.module)} each={module in extension.module} value="{module.code}" selected={module.code == setting.global.code}>{module.name}</option>
                <option if={_.isEmpty(extension.module)} value="{extension.code}" selected={extension.code == setting.global.code}>{extension.name}</option>
            </optgroup>
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
</vd-setting-block-module>