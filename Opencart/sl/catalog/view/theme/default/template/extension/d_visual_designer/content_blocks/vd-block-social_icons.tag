<vd-block-social_icons>
    <!--  <style>
        .vd-social-icon-container {
            display: flex;
            flex-flow: row;
        }
        .vd-social-icon-align-left {
            justify-content: flex-start;
        }
        .vd-social-icon-align-center {
            justify-content: center;
        }
        .vd-social-icon-align-right {
            justify-content: flex-end;
        }
        .vd-social-icon-container > .vd-social-icon{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vd-social-icon-container > .vd-social-icon:hover{
            text-decoration: none;
        }
    </style>  -->
    <div class="vd-social-icon-container {getState().className}">
        <virtual each="{icon, name in getState().icons}">
            <a if={icon.link} href="{icon.link}" target="_blank" class="vd-social-icon {name}"><i class="{icon.icon}" aria-hidden="true"></i></a>
        </virtual>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            className: ''
        })
        _.mixin({
            'sortKeysBy': function (obj, comparator) {
                var keys = _.sortBy(_.keys(obj), function (key) {
                    return comparator ? comparator(obj[key], key) : key;
                });
            
                return _.object(keys, _.map(keys, function (key) {
                    return obj[key];
                }));
            }
        });
        
        this.initState({
            icons: _.sortKeysBy(this.getState().setting.global.icons, function(icon){ return icon.sort_order})
        })

        this.initClass = function(){
            var className = []
            var setting = this.getState().setting
            if(setting.global.align) {
                className.push('vd-si-align-'+setting.global.align)
            }
            if(setting.global.icon_type) {
                className.push('vd-si-type-'+setting.global.icon_type)
            }
            if(setting.global.align_phone) {
                className.push('vd-si-phone-align-'+setting.global.align_phone)
            }
            if(setting.global.align_tablet) {
                className.push('vd-si-tablet-align-'+setting.global.align_tablet)
            }
            this.setState({
                className: className.join(' ')
            })
        }

        this.initStyle = function(){
            var setting = this.getState().setting
            var stylesMedia = {
                'phone': {
                    '.vd-social-icon': {
                        'margin-right': setting.global.margin_beetwen_phone
                    }
                },
                'tablet': {
                    '.vd-social-icon': {
                        'margin-right': setting.global.margin_beetwen_tablet
                    }
                },
                'desktop': {
                    '.vd-social-icon': {
                        'margin-right': setting.global.margin_beetwen
                    }
                }
            }
            var styles = {}
            for(var name in setting.global.icons) {
                var icon = setting.global.icons[name]
                if(setting.global.icon_type == 'circle') {
                    styles['.vd-social-icon.'+name] = {
                        'background-color': icon.background,
                        'width': 'calc('+icon.size+' + '+icon.size+')', 
                        'height': 'calc('+icon.size+' + '+icon.size+')'
                    }
                }
                styles['.vd-social-icon.'+name+' > i'] = {'color': icon.color, 'font-size': icon.size}
            }
            this.store.dispatch('block/style/update', {designer_id: this.getState().top.opts.id, block_id: this.opts.block.id, styles: styles})
            this.store.dispatch('block/style/media/update', {designer_id: this.getState().top.opts.id, block_id: this.opts.block.id, styles: stylesMedia})
        }

        this.initClass()

        this.on('update', function(){
            this.setState({
                 icons: _.sortKeysBy(this.getState().setting.global.icons, function(icon){ return icon.sort_order})
            })
            this.initClass()
        })

        this.on('updated', function(){
            this.initStyle();
        })


        this.on('mount', function(){
            this.initStyle();
        })


    </script>
</vd-block-social_icons>