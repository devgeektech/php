<vd-layout-section_accordion class="block-section accordion block-container {getState().className} {opts.block.id}" data-id="{opts.block.id}" id={getState().setting.id? getState().setting.id:null}>
    <div class="vd-panel-heading" onclick={activeSection}>
        <h4 class="vd-panel-title">
            <a class="section-heading">
                <i class="fa fa-plus" aria-hidden="true" if={!checkActive()}></i>
                <i class="fa fa-minus" aria-hidden="true" if={checkActive()}></i>
                <p class="title">{getState().setting.title}</p>
            </a>
        </h4>
    </div>
    <layout-style block={opts.block}/>
    <virtual if={getState().permission}>
        <div class="control control-section-accordion">
            <virtual data-is="control-buttons" block={opts.block}/>
        </div>
    </virtual>
    <div class="vd-panel-body">
        <div class="block-content clearfix {getState().block_config.setting.child? 'child' : ''}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    </div>
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

        activeSection(e) {
            var parentSetting = this.getState().parentSetting
            var permission = this.getState().permission
            var top = this.getState().top

            if(parentSetting.global.active_section == ($(this.root).index()+1)){
                parentSetting.global.active_section = 0
            } else {
                parentSetting.global.active_section = ($(this.root).index()+1)
            }
            this.setState({parentSetting: parentSetting})
            if(permission) {
                this.store.dispatch('block/setting/fastUpdate', {designer_id: top.opts.id, block_id: this.opts.block.parent, setting: parentSetting})
            } else {
                 var blocks = this.store.getState().blocks
                blocks[top.opts.id][this.opts.block.parent].setting = _.extend({}, parentSetting)
                this.store.updateState({blocks: blocks})
            }
            this.update()
        }
       
        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }

        this.checkActive = function(){
            var parentSetting = this.getState().parentSetting
            return Number(parentSetting.global.active_section) == ($(this.root).index()+1)
        }

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

        this.checkPermission()
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
            this.checkPermission()
            this.initClassNames()
            
        })
    </script>
</vd-layout-section_accordion>