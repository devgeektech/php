<vd-block-images_carousel>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>

    <div class="carousel">
        <virtual each={image, key in getState().setting.user.images}>
            <a href="{image.popup}" if={getState().setting.global.onclick == 'popup'}><img src="{image.thumb}"/></a>
            <a class="image-popup" href="{getLink(key)}" target={getState().setting.global.link_target=='new' ? '_blank': ''} if={getState().setting.global.onclick == 'link'}><img src="{image.thumb}"/></a>
            <a if={getState().setting.global.onclick != 'link' && getState().setting.global.onclick != 'popup'}><img src="{image.thumb}"/></a>
        </virtual>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initCarousel();
        })
        this.on('update', function(){
            var owl = $('.carousel', this.root).data('owlCarousel')
            if(!_.isUndefined(owl)) {
                owl.destroy()
            }
        })
        this.on('updated', function(){
            this.initCarousel();
        })
        this.getLink = function(number) {
            var setting = this.getState().setting
            var index = _.indexOf(_.map(_.keys(setting.user.images), function(value){return Number(value)}), Number(number))
            var links = setting.global.link.split(/\n/)
            if(!_.isUndefined(links[index])){
                return links[index]
            } else {
                return ''
            }
        }
        this.initCarousel = function(){
            var setting = this.getState().setting
            var options = {
                items: setting.global.slides_per_view,
                autoWidth: true,
                center:true,
                responsive:true,
                responsiveRefreshRate:200,
                navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>']
            };
            if(setting.global.slides_per_view == 1){
                options['singleItem'] = true;
            }
            if(setting.global.auto_play){
                options['autoPlay'] = setting.global.speed;
            }
            if(setting.global.animate){
                options['transitionStyle'] = setting.global.animate;
            }

            options['stopOnHover'] = setting.global.stopOnHover
            options['lazyLoad'] = setting.global.stopOnHover
            options['pagination'] = !setting.global.hide_pagination_control
            options['navigation'] = !setting.global.hide_next_prev_button
            options['loop'] = !setting.global.hide_next_prev_button
            if(setting.global.onclick == 'popup') {
                $('.carousel', this.root).magnificPopup({
                    type:'image',
                    delegate: 'a',
                    gallery: {
                        enabled:true
                    }
                });
            }

            $('.carousel', this.root).owlCarousel(options);


        }
    </script>
</vd-block-images_carousel>