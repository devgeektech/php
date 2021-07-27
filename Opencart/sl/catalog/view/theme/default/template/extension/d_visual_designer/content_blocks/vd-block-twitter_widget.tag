<vd-block-twitter_widget>
    <div style="text-align: center;" class="widget"></div>
    <script>
        this.mixin(new vd_block(this))
        this.initTwitter = function(){
            var setting = this.getState().setting
            var element = document.createElement('a')
            if(setting.global.height) {
                element.setAttribute('data-height', setting.global.height);
            }
            if(setting.global.width) {
                element.setAttribute('data-width', setting.global.width);
            }
            element.setAttribute('data-theme', setting.global.theme);
            element.setAttribute('data-link-color', setting.global.color_link);
            element.setAttribute('class', 'twitter-timeline');
            element.setAttribute('href', setting.global.href);

            $('.widget', this.root).html(element);
            twttr.widgets.load();
        }
        this.on('mount', function(){
            this.initTwitter()
        })
        this.on('update', function(){
            this.initTwitter()
        })
    </script>
</vd-block-twitter_widget>