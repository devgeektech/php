<vd-block-custom_heading>
    <div class="vd-custom-heading vd-custom-heading-align-{getState().setting.global.align}">
        <a href={getState().setting.global.link} if={getState().setting.link}>
            <raw html={'<'+getState().setting.global.tag+'>'+getState().setting.user.text+'</'+getState().setting.global.tag+'>'}/>
        </a>
        <div if={!getState().setting.global.link}><raw html={'<'+getState().setting.global.tag+'>'+getState().setting.user.text+'</'+getState().setting.global.tag+'>'}/></div>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('update', function(){
            this.initCustomHeading()
        })
        this.on('updated', function(){
            this.initCustomHeading()
        })
        this.on('mount', function(){
            this.initCustomHeading()
        })
        this.initCustomHeading = function(){
            var setting = this.getState().setting
            if(setting.global.font_family != 'default') {
                WebFont.load({
                    google: {
                        families: [setting.global.font_family, setting.global.font_family+' i7']
                    }
                });
            }
            var element = $('.vd-custom-heading', this.root)
            element.css({'color': setting.global.color})
            if(setting.global.font_family) {
                element.css({'font-family': setting.global.font_family}) 
            } else {
                element.css({'font-family': ''}) 
            }
            if(setting.global.bold) {
                element.css({'font-weight': '700'});
            } else {
                element.css({'font-weight': ''});
            }
            if(setting.global.italic) {
                element.css({'font-style': 'italic'});
            } else {
                element.css({'font-style': ''});
            }
            if(setting.global.underline) {
                element.css({'text-decoration': 'underline'});
            } else {
                element.css({'text-decoration': ''});
            }
            if(setting.global.line_height) {
                element.css({'line-height': setting.global.line_height});
            } else {
                element.css({'line-height': '100%'});
            }
            if(setting.global.font_size && _.indexOf(['div', 'p'], setting.global.tag) != -1) {
                element.css({'font-size': setting.global.font_size});
            } else {
                element.css({'font-size': ''});
            }
        }
        this.initCustomHeading()
    </script>
</vd-block-custom_heading>