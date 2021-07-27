<vd-block-facebook_like>
    <div class="vd-facebook-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
       
        this.on('mount', function(){
            this.initFacebookLike();
        })
        this.on('update', function(){
            this.initFacebookLike();
        })
        this.initFacebookLike = function(){
            var setting = this.getState().setting
            $('.vd-facebook-like', this.root).empty()
            $('.vd-facebook-like', this.root).sharrre({
                share: {
                    facebook: true,
                },
                buttons: {
                    facebook: {
                        layout: setting.global.type_button,
                        count: 'horizontal'
                    },
                },
                enableHover: false,
                enableCounter: false,
                enableTracking: true
            });
        }
    </script>
</vd-block-facebook_like>