<vd-layout-popup class="block-inner block-hot-corners block-container {getState().className} " data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
        <div class="block-mouse-toggle" if={getState().permission}></div>
        <div class="control control-{getState().block_config.setting.control_position} control-hot-corners" if={getState().permission && !getState().drag}>
            <virtual data-is="control-buttons" block={opts.block}/>
        </div>
        <div class="vd-border vd-border-left" if={getState().permission}></div>
        <div class="vd-border vd-border-top" if={getState().permission}></div>
        <div class="vd-border vd-border-right" if={getState().permission}></div>
        <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
     <virtual if={getState().permission}>
        <div class="vd-hot-corners-btn"><h4 class="block-title"><img  src="{getState().block_config.image}"/> {getState().block_config.title}</h4></div>
     </virtual>
    <div class="block-content clearfix {contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <div onClick={close} class="vd-hot-corners-overide"></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            permission: false,
            className: '',
            toogle_corner: null,
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false
        })

        this.initPopup = function(){
            $(document).on('mouseleave', 'body', function(e){
                var setting = this.getState().setting
                var toogle_corner = this.getState().toogle_corner
                var permission = this.getState().permission
                if(_.isNull(toogle_corner)){
                    var corner = '';
                    if(e.clientX >= e.screenX) {
                        corner = 'right';
                    }
                    if(e.clientX <= 0) {
                        corner = 'left';
                    }
                    if(e.clientY >= e.screenY) {
                        corner = 'bottom';
                    }
                    if(e.clientY <= 0) {
                        corner = 'top';
                    }

                    if (setting.active_corner && setting.active_corner != '*') {
                        if(corner == setting.active_corner){
                            if(!$(this.root).children('.block-content').hasClass('active')){
                            $(this.root).children('.block-content').addClass('active');
                            }
                        }
                    } else if(setting.active_corner && setting.active_corner == '*') {
                        if(!$(this.root).children('.block-content').hasClass('active')){
                            $(this.root).children('.block-content').addClass('active');
                        }
                    }
                    if(!permission){
                        this.setState('toogle_corner', 1);
                    }
                }
            }.bind(this))
            var setting = this.getState().setting
            if(setting.click_trigger && !_.isEmpty(setting.selector)){
                $(document).on('click', setting.selector, function(){
                    if(!$(this.root).children('.block-content').hasClass('active')){
                        $(this.root).children('.block-content').addClass('active');
                    }
                }.bind(this));
            }
        }

        close(e){
            $(this.root).children('.block-content').removeClass('active')
        }.bind(this)

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
            
            if(block_config.setting.child){
                contentClassName.push('child')
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

        this.initClassNames()
        this.checkPermission()

        this.on('mount', function(){
            this.initPopup()
        })

        this.on('update', function(){
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global,
                drag: this.store.getState().drag[this.getState().top.opts.id]
            })
            this.initClassNames()
            this.checkPermission()
        })
    </script>
</vd-layout-popup>