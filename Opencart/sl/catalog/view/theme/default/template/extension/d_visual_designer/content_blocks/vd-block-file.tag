<vd-block-file>
    <a href="{store.getOptions('blocks.file.download_url')}&download_id={getState().setting.global.download_id}"><span class="{getState().setting.global.icon}"></span> {getState().setting.global.title}</a>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function () {
            this.initStyle()
        })
        this.on('updated', function () {
            this.initStyle()
        })
        this.initStyle = function () {
            var setting = this.getState().setting
            $('a', this.root).css({
                'color': setting.global.color
            })
            $('span', this.root).css({
                'font-size': setting.global.icon_size
            })
        }
    </script>
</vd-block-file>