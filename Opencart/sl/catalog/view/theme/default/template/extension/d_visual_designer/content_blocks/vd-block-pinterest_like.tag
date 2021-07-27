<vd-block-pinterest_like>
    <div class="vd-pinterest-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
         this.on('mount', function(){
            this.initPinterestLike();
        })
        this.on('update', function(){
            this.initPinterestLike();
        })
        this.initPinterestLike = function(){
            var setting = this.getState().setting
            $('.vd-pinterest-like', this.root).empty()
            $('.vd-pinterest-like', this.root).sharrre({
                share: {
                    pinterest: true,
                },
                buttons: {
                    pinterest: {
                        layout: setting.global.type_button
                    },
                },
                enableHover: false,
                enableCounter: false,
                enableTracking: true
            });
        }
    </script>
</vd-block-pinterest_like>