<vd-block-conversion>
    <virtual>{setting.global.title?setting.global.title+' ':''}{store.getLocal('blocks.conversion.text_viewed')} - {setting.user.view}, {store.getLocal('blocks.conversion.text_conversions')} - {setting.user.conversion}, {store.getLocal('blocks.conversion.text_conversion_percentage')} - {setting.user.conversion_percentage}</virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.block_config = _.find(this.store.getState().config.blocks, function(block){
            return block.type == opts.block.type
        })
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-conversion>