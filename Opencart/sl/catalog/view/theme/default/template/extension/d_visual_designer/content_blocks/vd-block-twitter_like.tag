<vd-block-twitter_like>
    <div class="vd-twitter-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
         this.on('mount', function(){
            this.initTwitterLike();
        })
        this.on('update', function(){
            this.initTwitterLike();
        })
        this.initTwitterLike = function(){
            $('.vd-twitter-like', this.root).empty()
            $('.vd-twitter-like', this.root).sharrre({
                share: {
                    twitter: true,
                },
                buttons: {
                    twitter: {count: 'horizontal'},
                },
                enableHover: false,
                enableCounter: false,
                enableTracking: true
            });
        }
    </script>
</vd-block-twitter_like>