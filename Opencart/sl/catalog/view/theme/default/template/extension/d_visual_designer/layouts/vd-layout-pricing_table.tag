<vd-layout-pricing_table class="block-inner block-container block-pricing-table {getState().className}" data-id="{opts.block.id}" id={getState().setting.id? getState().setting.id:null}>
    <div class="block-mouse-toggle" if={getState().permission}></div>
    <div class="control control-{getState().block_config.setting.control_position} pricing-table-control" if={getState().permission && !getState().drag}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={getState().permission}></div>
    <div class="vd-border vd-border-top" if={getState().permission}></div>
    <div class="vd-border vd-border-right" if={getState().permission}></div>
    <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content block-pricing-table-content clearfix {opts.block.id} {getState().contentClassName}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            permission: false,
            className: '',
            contentClassName: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false,
            hoverDrag: false
        })

        this.initStyle = function() {
            var setting = this.getState().setting
             var style = {
                '.block-pricing-table-content .block-container': {
                    'margin-right': setting.width_between,
                },
                '.block-pricing-table-content .block-container:last-child': {
                    'margin-right': '0px',
                },
             }

             this.renderStyle(style)
        }

        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }

        this.initClassNames = function(){
            var className = []
            var contentClassName = []

            var setting = this.getState().setting
            var block_config = this.getState().block_config


            if(setting.background_video){
                className.push('video')
            }
            if(block_config.setting.child) {
                contentClassName.push('child')
            }
            if(setting.align_items){
                contentClassName.push('align-items-'+setting.align_items)
            }
            if(setting.design_show_on){
                className.push(_.map(setting.design_show_on, function(value){ return value }).join(' '))
            }
            if(setting.design_animate){
                className.push('animated '+setting.design_animate)
            }
            if(setting.additional_css_class){
                className.push(setting.additional_css_class)
            }
            this.setState({
                className: className.join(' '),
                contentClassName: contentClassName.join(' ')
            })
        }

        this.renderStyle = function(styles) {
            $('body').find('style#'+this.opts.block.id).remove();

            var styleTag = '<style id="'+this.opts.block.id+'">';

            for(var key in styles) {
                styleTag += '.'+this.opts.block.id+''+key+'{';
                for(var name in styles[key]){
                    styleTag += name + ':'+styles[key][name]+';'
                }
                styleTag += '}';
            }
            styleTag += '<style>'
            $('body').append(styleTag);
        }

        this.checkPermission()
        this.initClassNames()
        this.initStyle()

        this.on('update', function(){
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global,
                drag: this.store.getState().drag[this.getState().top.opts.id]
            })
            this.checkPermission()
            this.initClassNames()
            this.initStyle()
        })
    </script>
</vd-layout-pricing_table>