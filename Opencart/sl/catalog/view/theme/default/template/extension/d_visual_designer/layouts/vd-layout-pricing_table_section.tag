<vd-layout-pricing_table_section class="block-price-section block-container {getState().className}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="control control-{getState().block_config.setting.control_position}" if="{getState().permission && !getState().drag}" style="{getState().controlStyle}">
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <layout-style block={opts.block}/>
    <div class="block-content title {getState().block_config.setting.child? 'child' : ''} {opts.block.id} {getState().contentClassName}" data-is='vd-block-{opts.block.type}' block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            controlStyle: '',
            permission: false,
            className: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false
        })

        this.initClassNames = function(){
            var className = []
            var contentClassName = []
            var setting = this.getState().setting
            
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

        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }

        this.on('mount', function(){
            var margin_left = (-1)*($(this.root).children('.control').width()/2);
            var margin_top = (-1)*($(this.root).children('.control').height()/2);
            this.setState({
                controlStyle: 'margin:'+margin_top+'px 0 0 '+margin_left+'px;'
            })
            this.update()
        })

        this.on('update', function(){
            var margin_left = (-1)*($(this.root).children('.control').width()/2);
            var margin_top = (-1)*($(this.root).children('.control').height()/2)
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global,
                controlStyle: 'margin:'+margin_top+'px 0 0 '+margin_left+'px;',
                drag: this.store.getState().drag[this.getState().top.opts.id]
            })
            this.initClassNames()
            this.checkPermission()
        })

        this.initClassNames()
        this.checkPermission()
    </script>
</vd-layout-pricing_table_section>