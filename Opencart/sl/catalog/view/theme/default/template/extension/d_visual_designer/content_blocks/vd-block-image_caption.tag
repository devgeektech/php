<vd-block-image_caption>
    <div class="vd-image-caption-container vd-image-caption-text-{getState().setting.global.position_text} {getState().setting.global.size=='responsive'?'responsive':''}">
        <div class="vd-image-caption-text {_.indexOf(['top', 'left'], getState().setting.global.position_text)}" if={_.indexOf(['top', 'left'], getState().setting.global.position_text) != -1}>
            <h4 if={getState().setting.global.title}>{getState().setting.global.title}</h4>
            <p class="vd-image-caption-main-text"><raw html={getState().setting.user.text}/></p>
        </div>

        <div class="vd-image-caption-wrapper vd-image-caption-size-{getState().setting.global.size}">
            <div class="vd-image-caption vd-animate-{getState().setting.global.animate}">
                <a if={getState().setting.global.onclick == 'popup'} class="image-popup" href="{getState().setting.user.popup}">
                    <img src="{getState().setting.user.thumb}" width="{getState().setting.user.width}" height="{getState().setting.user.height}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
                </a>
                <a if={getState().setting.global.onclick == 'link'} class="image-popup" href="{getState().setting.global.link}" target={getState().setting.global.link_target == 'new' ? '_blank' : '_self'}>
                    <img src="{getState().setting.user.thumb}" width="{getState().setting.user.width}" height="{getState().setting.user.height}" alt="{setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
                </a>
                <img if={getState().setting.global.onclick == ''} src="{getState().setting.user.thumb}" width="{getState().setting.user.width}" height="{getState().setting.user.height}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
            </div>
        </div>
        <div class="vd-image-caption-text vd-image-caption-text-{getState().setting.global.position_text}" if={_.indexOf(['bottom', 'right'], getState().setting.global.position_text) != -1}>
            <h4 if={getState().setting.global.title}>{getState().setting.global.title}</h4>
            <p class="vd-image-caption-main-text"><raw html={getState().setting.user.text}/></p>
        </div>
</div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initPopup()
        })
        this.on('updated', function(){
            this.initPopup()
        })
        this.initPopup = function(){
            var setting = this.getState().setting
            if(setting.global.padding_text) {
                $('.vd-image-caption-text', this.root).css({'padding':setting.global.padding_text})
            } else {
                $('.vd-image-caption-text', this.root).css({'padding':''})
            }
            
            if(!setting.global.display_border) {
                $('.vd-image-caption-container', this.root).css({'border-width': 0})
            } else {
                $('.vd-image-caption-container', this.root).css({'border-width': ''})
            }
            if(setting.global.onclick == 'popup') {
                $('.vd-image-caption', this.root).magnificPopup({
                        type:'image',
                        delegate: 'a',
                        gallery: {
                            enabled:true
                        }
                });
            }
        }
    </script>
</vd-block-image_caption>