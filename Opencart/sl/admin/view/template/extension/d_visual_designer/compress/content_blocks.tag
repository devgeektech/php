<vd-block-accordion>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-accordion>
<vd-block-column>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-column>
<vd-block-column_inner>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-column_inner>
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
<vd-block-pricing_table>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-pricing_table>
<vd-block-row>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-row>
<vd-block-row_inner>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-row_inner>
<vd-block-section>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-section>
<vd-block-section_accordion>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
    </script>
</vd-block-section_accordion>
<vd-block-section_wrapper>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-section_wrapper>
<vd-block-tabs>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-tabs>
<vd-block-tour>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.setting = this.opts.block.setting
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
    </script>
</vd-block-tour>
