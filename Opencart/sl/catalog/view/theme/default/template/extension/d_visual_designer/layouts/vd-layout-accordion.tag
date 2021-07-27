<vd-layout-accordion class="block-inner block-container {getState().className} {opts.block.id}"  data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <layout-style block={opts.block}/>
    <h4 if={getState().setting.title}>{getState().setting.title}</h4>
    <div class="block-content clearfix {getState().block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <virtual if={getState().permission}>
        <div class="vd-tab accordion-control">
            <virtual data-is="control-buttons" block={opts.block}/>
        </div>
    </virtual>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            permission: false,
            className: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            })
        })

        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }

        this.initClassNames = function(){
            var className = []
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
            this.setState({className: className.join(' ')})
        }

        this.checkPermission()
        this.initClassNames()

        this.on('update', function(){
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global
            })
            this.checkPermission()
            this.initClassNames()
        })
    </script>
</vd-layout-accordion>