<vd-block-button>
    <div class="button vd-button-container vd-button-align-{getState().setting.global.alignment}">
        <button onclick="{getState().setting.user.action}" class="vd-button {getState().setting.global.display_icon?'vd-button-icon':''} {getState().setting.global.display_icon_hover?'vd-button-icon-hover':''}">
            <span class="{getState().setting.global.icon} left" if={getState().setting.global.icon_align == 'left'}></span>
            <span class="title">{getState().setting.global.text}</span>
            <span class="{getState().setting.global.icon} right" if={getState().setting.global.icon_align == 'right'}></span>
        </button>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('update', function(){
            this.initButton()
        })

        this.initButton = function(){
            var setting = this.getState().setting
            if(setting.global.font_family != 'default') {
                WebFont.load({
                    google: {
                        families: [setting.global.font_family, setting.global.font_family+' i7']
                    }
                });
            }
            $('.vd-button', this.root).css({
                'background-color': setting.global.color,
                'color': setting.global.color_text,
                'border-width': setting.global.border_width,
                'border-style': 'solid',
                'border-color': setting.global.border_color,
                'border-radius': setting.global.border_radius,
                'font-size': setting.global.font_size,
                'letter-spacing': setting.global.letter_spacing
                });

            $('.vd-button', this.root).hover(function(){
                $('.vd-button', this.root).css({
                  'background-color': setting.global.color_hover,
                  'border-color': setting.global.border_color_hover,
                  'color': setting.global.color_text_hover
                })
            }.bind(this), function(){
                 $('.vd-button', this.root).css({
                  'background-color': setting.global.color,
                  'border-color': setting.global.border_color,
                  'color': setting.global.color_text
                })
            }.bind(this))
            if(setting.global.font_family != 'default') {
                $('.vd-button', this.root).css({'font-family': setting.global.font_family});
            } else {
                $('.vd-button', this.root).css({'font-family': ''});
            }
            if(setting.global.bold) {
                $('.vd-button', this.root).css({'font-weight': '700'});
            } else {
                $('.vd-button', this.root).css({'font-weight': ''});
            }
            if(setting.global.italic) {
                $('.vd-button', this.root).css({'font-style': 'italic'});
            } else {
                $('.vd-button', this.root).css({'font-style': ''});
            }
            if(setting.global.underline) {
                $('.vd-button', this.root).css({'text-decoration': 'underline'});
            } else {
                $('.vd-button', this.root).css({'text-decoration': ''});
            }
            if(setting.global.full_width) {
                $('.vd-button', this.root).css({'width': '100%'});
            } else if(setting.global.width) {
                $('.vd-button', this.root).css({'width': setting.global.width});
            } else {
                $('.vd-button', this.root).css({'width': ''});
            }
            if(setting.global.height) {
                $('.vd-button', this.root).css({'height': setting.global.height});
            } else {
                $('.vd-button', this.root).css({'height': ''});
            }
            $('span:not(.title)', this.root).css({'color': setting.global.icon_color})
        }
        this.on('mount', function(){
            this.initButton()
        })

    </script>
</vd-block-button>