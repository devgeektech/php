<vd-layout-tour cclass="block-inner block-container {getState().className} {opts.block.id}"  data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <layout-style block={opts.block}/>
    <h4 if={getState().setting.title}>{getState().setting.title}</h4>
    <div class="block-content tour-content clearfix {getState().block_config.setting.child? 'child' : ''}" ref="content">
        <ul class="vd-tour-list">
            <li class="vd-tab {(key+1) == getState().setting.active_section?'active':''}" each={childBlock, key in getState().childBlocks}>
                <a class="tab" onclick={activeSection}><span class="vd-tab-title">{childBlock.setting.global.title}</span></a>
                <virtual if={getState().permission}>
                    <virtual data-is="control-buttons" block={childBlock}/>
                </virtual>
            </li>
            <virtual if={getState().permission}>
                <li class="vd-tab tab-control">
                    <virtual data-is="control-buttons" block={opts.block}/>
                </li>
            </virtual>
        </ul>
        <div class="vd-tour-wrapper" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    </div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            permission: false,
            className: '',
            childBlocks: {},
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

        activeSection(e) {
            var setting = this.opts.block.setting
            setting.global.active_section = ($(e.target).closest('.vd-tab').index()+1)
            var top = this.getState().top
            var permission = this.getState().permission
            if(permission) {
                this.store.dispatch('block/setting/fastUpdate', {designer_id: top.opts.id, block_id: this.opts.block.id, setting: setting})
            } else {
                var blocks = this.store.getState().blocks
                blocks[top.opts.id][this.opts.block.id].setting = _.extend({},setting)
                this.store.updateState({blocks: blocks})
            }
            this.update()
        }


        this.initChildBlocks = function(){
            this.setState({childBlocks: this.store.getBlocks(this.parent.getState().top.opts.id, this.opts.block.id)})
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

        this.initChildBlocks()
        this.checkPermission()
        this.initClassNames()

        this.on('update', function(){
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global
            })
            this.initChildBlocks()
            this.checkPermission()
            this.initClassNames()
        })
    </script>
</vd-layout-tour>