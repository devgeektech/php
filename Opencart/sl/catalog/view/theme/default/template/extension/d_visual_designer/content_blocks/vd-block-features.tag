<vd-block-features>
    <div class="vd-features-container image-style-{getState().setting.global.image_position}">
        <div class="image vd-image-size-{getState().setting.global.size}">
            <img src="{getState().setting.user.thumb}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
            <div class="title h3" if={getState().setting.global.image_position == 'left_top'}>{getState().setting.global.title}</div>
        </div>
        <div class="text-container" if={getState().setting.global.image_position == 'left'}>
            <div class="title h3" if={getState().setting.global.image_position != 'left_top'}>{getState().setting.global.title}</div>
            <div class="text"><raw html="{getState().setting.global.text}"/></div>
        </div>
        <div if={getState().setting.global.image_position != 'left'}>
            <div class="title h3" if={getState().setting.global.image_position != 'left_top'}>{getState().setting.global.title}</div>
            <div class="text"><raw html="{getState().setting.global.text}"/></div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))

        this.initStyle = function() {
           var setting = this.getState().setting

            if(setting.global.size == 'custom') {
                $('.image > img',this.root).css({width: setting.global.width, height: setting.global.height})
            } else {
                $('.image > img',this.root).css({width: '', height: ''})
            }
        }

        this.on('mount', function () {
            this.initStyle()
        })

        this.on('updated', function () {
            this.initStyle()
        })
    </script>
</vd-block-features>