<vd-block-facebook_widget>
    <iframe class="fb-page" src="//www.facebook.com/plugins/page.php?{getState().url}&appId" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            url: ''
        })
        this.on('update', function(){
            this.initFacebook()
        })

        this.on('updated', function(){
            this.initStyle();
        })

        this.on('mount', function(){
            this.initStyle();
        })

        this.initFacebook = function(){
            var setting = this.getState().setting

            var url = [];
            url.push('href='+encodeURIComponent(setting.global.href))
            url.push('tabs='+_.values(setting.global.tabs).join(','))
            url.push('small_header='+setting.global.small_header)
            url.push('adapt_container_width=true')
            url.push('hide_cover=false')
            url.push('show_facepile='+setting.global.show_facepile)

            if(setting.global.height) {
                url.push('height='+setting.global.height)
            }
            if(setting.global.width) {
                 url.push('width='+setting.global.width)
            }
            this.setState({url: url.join('&')})
        }

        this.initStyle = function(){
            var setting = this.getState().setting

            if(setting.global.height) {
                $('.fb-page', this.root).css({'height': setting.global.height})
            } else {
                $('.fb-page', this.root).css({'height': ''})
            }
            if(setting.global.width) {
                $('.fb-page', this.root).css({'width': setting.global.width})
            } else {
                $('.fb-page', this.root).css({'width': ''})
            }
        }

        this.initFacebook();
    </script>
</vd-block-facebook_widget>