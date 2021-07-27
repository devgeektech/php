<vd-block-icon>
    <div class="container-fluid vd-icon-container vd-icon-align-{getState().setting.global.align} {getState().setting.global.align_phone ? 'vd-icon-align-phone-' + setting.global.align_phone: '' } {getState().setting.global.align_tablet ? 'vd-icon-align-tablet-' + getState().setting.global.align_tablet : '' }">
        <a href="{getState().setting.global.link}" style="text-decoration: none;" if={getState().setting.global.link}>
            <div class="vd-icon vd-icon-block vd-icon-{getState().setting.global.size} {getState().setting.global.size_phone ? 'vd-icon-phone-' + getState().setting.global.size_phone : ''} {getState().setting.global.size_tablet ? 'vd-icon-tablet-' + getState().setting.global.size_tablet : ''} vd-icon-style-{getState().setting.global.background_style}">
                <span class="{getState().setting.global.icon}" style="color:{getState().setting.global.color};"></span>
            </div>
        </a>
        <div if={!getState().setting.global.link} class="vd-icon vd-icon-block vd-icon-{getState().setting.global.size} {getState().setting.global.size_phone ? 'vd-icon-phone-' + getState().setting.global.size_phone : ''} {getState().setting.global.size_tablet ? 'vd-icon-tablet-' + getState().setting.global.size_tablet : ''} vd-icon-style-{getState().setting.global.background_style}">
            <span class="{getState().setting.global.icon}" style="color:{getState().setting.global.color};"></span>
        </div>
</div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initIcon()
        })
        this.on('update', function(){
            this.initIcon()
        })
        this.initIcon = function(){
            var setting = this.getState().setting
            $('.vd-icon-style-rounded', this.root).css({'background': setting.global.background_color})
            $('.vd-icon-style-boxed', this.root).css({'background': setting.global.background_color})
            $('.vd-icon-style-rounded-less', this.root).css({'background': setting.global.background_color})
            $('.vd-icon-style-rounded-outline', this.root).css({'border-color': setting.global.background_color})
            $('.vd-icon-style-boxed-outline', this.root).css({'border-color': setting.global.background_color})
            $('.vd-icon-style-rounded-less-outline', this.root).css({'border-color': setting.global.background_color})
        }
    </script>
</vd-block-icon>