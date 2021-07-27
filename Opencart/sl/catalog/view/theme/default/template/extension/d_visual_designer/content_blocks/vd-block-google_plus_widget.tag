<vd-block-google_plus_widget>
    <div style="text-align: center;" class="widget"></div>
    <script>
        this.mixin(new vd_block(this))
        this.initGoogle = function(){
            var setting = this.getState().setting
            window.___gcfg = {lang: 'ru', parsetags: 'explicit'};
            var element = document.createElement('div')
            element.setAttribute('data-href', setting.global.href);
            element.setAttribute('data-theme', setting.global.theme);
            element.setAttribute('data-layout', setting.global.layout);
            element.setAttribute('data-showtagline', setting.global.showtagline);
            element.setAttribute('class', 'g-' + setting.global.badge_type);

            $('.widget', this.root).html(element);
            gapi[setting.global.badge_type].go()
        }
        this.on('mount', function(){
            this.initGoogle()
        })
        this.on('update', function(){
            this.initGoogle()
        })
    </script>
</vd-block-google_plus_widget>