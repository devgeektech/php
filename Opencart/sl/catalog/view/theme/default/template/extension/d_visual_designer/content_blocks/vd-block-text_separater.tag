<vd-block-text_separater>
    <div class="vd-text-separater-container vd-text-separater-align-{getState().setting.global.align}">
        <div class="vd-text-separater-wrapper">
            <div class="vd-text-separater left" if={_.indexOf(['right', 'center'], getState().setting.global.title_align) != -1}>
                <hr/>
            </div>
            <h2 class="vd-separater-title">{getState().setting.global.title}</h2>
            <div class="vd-text-separater right" if={_.indexOf(['left', 'center'], getState().setting.global.title_align) != -1}>
                <hr/>
            </div>
        </div>
    </div>

    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initSeparator()
        })
        this.on('updated', function(){
            this.initSeparator();
        })
        this.initSeparator = function(){
            var setting = this.getState().setting
            if(setting.global.font_family != 'default') {
                WebFont.load({
                    google: {
                        families: [setting.global.font_family, setting.global.font_family+' i7']
                    }
                });
            }
            $('.vd-separater-title', this.root).css({'color': setting.global.color_title})
            if(setting.global.font_family) {
                $('.vd-separater-title', this.root).css({'font-family': setting.global.font_family}) 
            } else {
                $('.vd-separater-title', this.root).css({'font-family': ''}) 
            }
            if(setting.global.font_size) {
                $('.vd-separater-title', this.root).css({'font-size': setting.global.font_size})
            } else {
                $('.vd-separater-title', this.root).css({'font-size': ''})
            }
            if(setting.global.bold) {
                 $('.vd-separater-title', this.root).css({'font-weight': '700'});
            } else {
                 $('.vd-separater-title', this.root).css({'font-weight': ''});
            }
            if(setting.global.italic) {
                 $('.vd-separater-title', this.root).css({'font-style': 'italic'});
            } else {
                 $('.vd-separater-title', this.root).css({'font-style': ''});
            }
            if(setting.global.underline) {
                $('.vd-separater-title', this.root).css({'text-decoration': 'underline'});
            } else {
                $('.vd-separater-title', this.root).css({'text-decoration': ''});
            }
            $('.vd-text-separater-wrapper').css({'flex': '0 0 '+setting.global.width})
            $('hr', this.root).css({'border-top': setting.global.border_width+' '+setting.global.border_style+' '+setting.global.border_color})
        }
    </script>
</vd-block-text_separater>