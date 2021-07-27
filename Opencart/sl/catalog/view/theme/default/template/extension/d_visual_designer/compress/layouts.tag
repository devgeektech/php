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
<vd-layout-children class="block-child block-container {getState().className}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="control control-{getState().block_config.setting.control_position}" if="{getState().permission && !getState().drag}" style="{getState().controlStyle}">
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().block_config.setting.child? 'child' : ''} {getState().contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            controlStyle: '',
            permission: false,
            className: '',
            contentClassName: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false
        })

        this.checkControl = function () {
            var parent = this.opts.block.parent
            if($(this.root).height() < 100) {
                this.store.dispatch('block/control/up', {id: parent})
            } else {
                this.store.dispatch('block/control/normal', {id: parent})
            }
        }

        $(this.root).on('mouseenter', function () {
            if(!this.getState().drag){
                this.checkControl()
            }
        }.bind(this))

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
        this.initClassNames()
        this.checkPermission()
        this.on('mount', function(){
            var margin_left = (-1)*($(this.root).children('.control').width()/2);
            var margin_top = (-1)*($(this.root).children('.control').height()/2);
            this.setState({
                controlStyle: 'margin:'+margin_top+'px 0 0 '+margin_left+'px;'
            })
        })
        this.on('update', function(){
            var margin_left = (-1)*($(this.root).children('.control').width()/2);
            var margin_top = (-1)*($(this.root).children('.control').height()/2)
            this.setState({
                block_config: _.find(this.store.getState().config.blocks, function(block){
                    return block.type == opts.block.type
                }),
                setting: this.opts.block.setting.global,
                drag: this.store.getState().drag[this.getState().top.opts.id],
                controlStyle: 'margin:'+margin_top+'px 0 0 '+margin_left+'px;'
            })
            this.initClassNames()
            this.checkPermission()
        })
    </script>
</vd-layout-children>
<vd-layout-column_inner class="block-inner block-container {getState().className}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="block-mouse-toggle" if={getState().permission}></div>
    <div class="control control-{getState().block_config.setting.control_position} {getState().downControl? 'control-down': ''}" if={getState().permission && ! getState().drag}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={getState().permission}></div>
    <div class="vd-border vd-border-top" if={getState().permission}></div>
    <div class="vd-border vd-border-right" if={getState().permission}></div>
    <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            activeControl: false,
            downControl: false,
            permission: false,
            className: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false,
            hoverDrag: false
        })

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                this.store.dispatch('block/control/up', {id: parent})
                this.setState('downControl', true)
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                this.store.dispatch('block/control/normal', {id: parent})
                this.setState('downControl', false)
                this.update()
            }
        }.bind(this))

        this.initClassNames = function(){
            var className = []
            var contentClassName = []
            var setting = this.getState().setting

            if(this.getState().block_config.setting.child) {
                contentClassName.push('child')
            }
            if(this.getState().block_config.setting.child_blocks) {
                contentClassName.push('child-blocks')
            }

            if(setting.offset){
                className.push('offset-lg-'+setting.offset)
            }
            if(setting.offset_phone){
                className.push('offset-'+setting.offset_phone)
            }
            if(setting.offset_tablet){
                className.push('offset-md-'+setting.offset_tablet)
            } else if (setting.offset){
                className.push('offset-md-'+setting.offset)
            }

           if(setting.order){
                className.push('order-lg-'+setting.order)
            }
            if(setting.order_phone){
                className.push('order-'+setting.order_phone)
            }
            if(setting.order_tablet){
                className.push('order-md-'+setting.order_tablet)
            } else if (setting.order){
                className.push('order-md-'+setting.order)
            }

            if(!_.isUndefined(setting.size)){
                if(setting.size == 'fill') {
                    className.push('col-lg')
                } else {
                    className.push('col-lg-'+setting.size)
                }
            }
            if(setting.size_phone){
                if(setting.size_phone == 'fill') {
                    className.push('col')
                } else {
                    className.push('col-'+setting.size_phone)
                }
            }
            if(setting.size_tablet){
                if(setting.size_tablet == 'fill') {
                    className.push('col-md')
                } else {
                    className.push('col-md-'+setting.size_tablet)
                }
            } else if(setting.size){
                if(setting.size == 'fill') {
                    className.push('col-md')
                } else {
                    className.push('col-md-'+setting.size)
                }
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

        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }
        this.initClassNames()
        this.checkPermission()

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
</vd-layout-column_inner>
<vd-layout-main-wrapper class="block-parent-wrapper block-container {getState().className}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="block-mouse-toggle" if={getState().permission}></div>
    <div class="control control-{getState().block_config.setting.control_position} {getState().upControl?'control-up':null}"  if={getState().permission && !getState().drag}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={getState().permission}></div>
    <div class="vd-border vd-border-top" if={getState().permission}></div>
    <div class="vd-border vd-border-right" if={getState().permission}></div>
    <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            upControl: false,
            permission: false,
            className: '',
            contentClassName: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false
        })

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                this.setState('upControl', true)
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id) {
                this.setState('upControl', false)
                this.update()
            }
        }.bind(this))

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

            if(this.getState().setting.background_video){
                className.push('video')
            }
            if(this.getState().block_config.setting.child) {
                contentClassName.push('child')
            }
            if(this.getState().block_config.setting.child_blocks) {
                contentClassName.push('child-blocks')
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

        this.checkPermission()
        this.initClassNames()


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
        })
    </script>
</vd-layout-main-wrapper>
<vd-layout-main class="block-parent block-container {getState().className}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="block-mouse-toggle" if={getState().permission}></div>
    <div class="control control-{getState().block_config.setting.control_position} {getState().upControl?'control-up':null}"  if={getState().permission && !getState().drag}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={getState().permission}></div>
    <div class="vd-border vd-border-top" if={getState().permission}></div>
    <div class="vd-border vd-border-right" if={getState().permission}></div>
    <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            upControl: false,
            permission: false,
            className: '',
            contentClassName: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false
        })

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                this.setState('upControl', true)
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id) {
                this.setState('upControl', false)
                this.update()
            }
        }.bind(this))

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

            if(this.getState().setting.background_video){
                className.push('video')
            }
            if(this.getState().block_config.setting.child) {
                contentClassName.push('child')
            }
            if(this.getState().block_config.setting.child_blocks) {
                contentClassName.push('child-blocks')
            }
            if(setting.align){
                if(setting.align == 'left'){
                    contentClassName.push('justify-content-start')
                }
                if(setting.align == 'center'){
                    contentClassName.push('justify-content-center')
                }
                if(setting.align == 'right'){
                    contentClassName.push('justify-content-end')
                }
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

        this.checkPermission()
        this.initClassNames()


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
        })
    </script>
</vd-layout-main>
<vd-layout-medium class="block-inner block-container {getState().className}" data-id="{opts.block.id}" id={getState().setting.id? getState().setting.id:null}>
    <div class="block-mouse-toggle" if={getState().permission}></div>
    <div class="control control-{getState().block_config.setting.control_position} {getState().upControl? 'control-up': ''}" if={getState().permission && !getState().drag}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={getState().permission}></div>
    <div class="vd-border vd-border-top" if={getState().permission}></div>
    <div class="vd-border vd-border-right" if={getState().permission}></div>
    <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block}></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            upControl: false,
            permission: false,
            className: '',
            contentClassName: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false,
            hoverDrag: false
        })

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                this.store.dispatch('block/control/up', {id: parent})
                this.setState('upControl', true)
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                this.store.dispatch('block/control/normal', {id: parent})
                this.setState('upControl', false)
                this.update()
            }
        }.bind(this))

        this.initClassNames = function(){
            var className = []
            var contentClassName = []
            var setting = this.getState().setting

            if(this.getState().block_config.setting.child) {
                contentClassName.push('child')
            }
            if(this.getState().block_config.setting.child_blocks) {
                contentClassName.push('child-blocks')
            }

            if(setting.offset){
                className.push('offset-lg-'+setting.offset)
            }
            if(setting.offset_phone){
                className.push('offset-'+setting.offset_phone)
            }
            if(setting.offset_tablet){
                className.push('offset-md-'+setting.offset_tablet)
            } else if (setting.offset){
                className.push('offset-md-'+setting.offset)
            }

            if(setting.order){
                className.push('order-lg-'+setting.order)
            }
            if(setting.order_phone){
                className.push('order-'+setting.order_phone)
            }
            if(setting.order_tablet){
                className.push('order-md-'+setting.order_tablet)
            } else if (setting.order){
                className.push('order-md-'+setting.order)
            }

            if(!_.isUndefined(setting.size)){
                if(setting.size == 'fill') {
                    className.push('col-lg')
                } else {
                    className.push('col-lg-'+setting.size)
                }
            }
            if(setting.size_phone){
                if(setting.size_phone == 'fill') {
                    className.push('col')
                } else {
                    className.push('col-'+setting.size_phone)
                }
            }
            if(setting.size_tablet){
                if(setting.size_tablet == 'fill') {
                    className.push('col-md')
                } else {
                    className.push('col-md-'+setting.size_tablet)
                }
            } else if(setting.size){
                if(setting.size == 'fill') {
                    className.push('col-md')
                } else {
                    className.push('col-md-'+setting.size)
                }
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

        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }
        this.initClassNames()
        this.checkPermission()

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
</vd-layout-medium>
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
<vd-layout-row_inner class="block-inner block-container {getState().className}" data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <div class="block-mouse-toggle" if={getState().permission}></div>
    <div class="control control-{getState().block_config.setting.control_position} {getState().downControl?'control-down':null}" if={getState().permission && !getState().drag}>
        <virtual data-is="control-buttons" block={opts.block}/>
    </div>
    <div class="vd-border vd-border-left" if={getState().permission}></div>
    <div class="vd-border vd-border-top" if={getState().permission}></div>
    <div class="vd-border vd-border-right" if={getState().permission}></div>
    <div class="vd-border vd-border-bottom" if={getState().permission}></div>
    <layout-style block={opts.block}/>
    <div class="block-content {getState().contentClassName} {opts.block.id}" data-is="vd-block-{opts.block.type}" block={opts.block} ref="content"></div>
    <script>
        this.mixin(new vd_component(this, false))
        this.initState({
            setting: this.opts.block.setting.global,
            activeControl: false,
            downControl: false,
            permission: false,
            className: '',
            contentClassName: '',
            block_config: _.find(this.store.getState().config.blocks, function(block){
                return block.type == opts.block.type
            }),
            drag: false,
            hoverDrag: false
        })

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                this.store.dispatch('block/control/up', {id: parent})
                this.setState({downControl: true})
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id) {
                this.store.dispatch('block/control/normal', {id: parent})
                this.setState({downControl: false})
                this.update()
            }
        }.bind(this))

        this.checkPermission = function(){
            var top = this.getState().top
            var block_config = this.getState().block_config
            if(this.store.getState().config.permission[top.opts.id] && block_config.setting.display_control){
                this.setState('permission', true)
            }
        }

        this.store.subscribe('block/control/up', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                this.store.dispatch('block/control/up', {id: parent})
                this.setState('upControl', true)
                this.update()
            }
        }.bind(this))

        this.store.subscribe('block/control/normal', function(data){
            if(data.id == this.opts.block.id) {
                var parent = this.opts.block.parent
                this.store.dispatch('block/control/normal', {id: parent})
                this.setState('upControl', false)
                this.update()
            }
        }.bind(this))

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
            if(setting.align){
                if(setting.align == 'left'){
                    contentClassName.push('justify-content-start')
                }
                if(setting.align == 'center'){
                    contentClassName.push('justify-content-center')
                }
                if(setting.align == 'right'){
                    contentClassName.push('justify-content-end')
                }
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

        this.checkPermission()
        this.initClassNames()


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
        })
    </script>
</vd-layout-row_inner>
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
<vd-layout-tabs class="block-inner block-container {getState().className} {opts.block.id}"  data-id="{opts.block.id}" id="{getState().setting.id? getState().setting.id:null}">
    <layout-style block={opts.block}/>
    <h4 if={getState().setting.title}>{getState().setting.title}</h4>
    <div class="block-content tabs-content clearfix {getState().block_config.setting.child? 'child' : ''}" ref="content">
        <ul class="vd-tabs-list vd-align-{getState().setting.align}">
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
        <div data-is="vd-block-{opts.block.type}" block={opts.block}></div>
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
</vd-layout-tabs>
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
