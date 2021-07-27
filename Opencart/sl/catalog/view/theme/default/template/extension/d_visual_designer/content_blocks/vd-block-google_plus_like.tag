<vd-block-google_plus_like>
    <div class="vd-google-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initGoogleLike();
        })
        this.on('update', function(){
            this.initGoogleLike();
        })
        this.initGoogleLike = function(){
            var setting = this.getState().setting
            $('.vd-google-like', this.root).empty()
            $('.vd-google-like', this.root).sharrre({
                 share: {
                    googlePlus: true,
                },
                buttons: {
                    googlePlus: {
                        size: setting.global.size,
                        annotation:'bubble'
                    },
                }
            });
        }
    </script>
</vd-block-google_plus_like>