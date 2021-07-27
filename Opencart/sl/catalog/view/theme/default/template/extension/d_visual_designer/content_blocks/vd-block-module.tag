<vd-block-module>
    <raw html={getState().setting.user.text}/>
    <link href="{link.href}" rel="{link.rel}" each={link in getState().setting.user.styles}/>
    <div class="scripts"></div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            setTimeout(function(){
                $('.scripts', this.root).empty();
                for(var key in setting.user.scripts) {
                    var script = document.createElement('script')
                    script.src=setting.user.scripts[key]
                    script.type="text/javascript"
                    $('.scripts', this.root).append(script)
                }
            }.bind(this), 100)
            var setting = this.getState().setting

        })
        this.on('updated', function(){
            var setting = this.getState().setting
            $('.scripts', this.root).empty();
            for(var key in setting.user.scripts) {
                var script = document.createElement('script')
                script.src=setting.user.scripts[key]
                script.type="text/javascript"
                $('.scripts', this.root).append(script)
            }
        })
    </script>
</vd-block-module>