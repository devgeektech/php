<vd-setting-block-product>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.product.entry_product')}</label>
        <div class="fg-setting">
            <vd-autocomplete name="product_id" value={setting.global.product_id} url={store.getOptions('blocks.product.autocomplete')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.product.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{key}" selected={key == setting.global.style} each={value, key in store.getOptions('blocks.product.styles')}>{value}</option>
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
</vd-setting-block-product>