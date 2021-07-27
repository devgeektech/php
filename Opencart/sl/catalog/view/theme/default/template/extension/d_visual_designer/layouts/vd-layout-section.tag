<vd-layout-section class="block-section block-container {getState().className} {opts.block.id}" data-id="{opts.block.id}" id={getState().setting.id? getState().setting.id:null}>
    <layout-style block={opts.block}/>
    <div class="block-content clearfix {getState().block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            className: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            parentSetting: this.store.getState().blocks[this.getState().top.opts.id][this.opts.block.parent].setting
        })

        this.initClassNames = function(){
            var className = []
            var setting = this.getState().setting
            if(this.checkActive()){
                className.push('active')
            }
            if(setting.background_video){
                className.push('video')
            }
            if(setting.design_show_on){
                className.push(_.map(setting.design_show_on, function(value){ return value }).join(' '))
            }
            if(setting.design_animate){
                className.push('animated '+this.setting.design_animate)
            }
            if(setting.additional_css_class){
                className.push(setting.additional_css_class)
            }
            this.setState({className: className.join(' ')})
        }

        this.checkActive = function(){
            var parentSetting = this.getState().parentSetting
            return Number(parentSetting.global.active_section) == ($(this.root).index()+1)
        }

        this.initClassNames()

        this.on('mount', function(){
            this.initClassNames()
            this.update();
        })

        this.on('update', function(){
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                parentSetting: this.store.getState().blocks[this.getState().top.opts.id][this.opts.block.parent].setting,
                setting: this.opts.block.setting.global
            })
            this.initClassNames()
            
        })
    </script>
</vd-layout-section>