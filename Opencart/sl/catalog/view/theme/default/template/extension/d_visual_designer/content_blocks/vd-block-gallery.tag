<vd-block-gallery>
    <style>
        .gallery > li > img {
            height: auto;
            max-width: 100%;
            display: block;
            vertical-align: middle;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <ul class="gallery" if={_.indexOf(['slider_fade', 'slider_slide'], getState().setting.global.type_gallery) != -1}>
        <li each={image in getState().setting.user.images} data-thumb="{image.thumb}" data-src="{image.popup}">
            <img src="{image.thumb}"/>
        </li>
    </ul>
    <div class="gallery" if={getState().setting.global.type_gallery == 'image_grid'}>
        <virtual each={image in getState().setting.user.images}>
            <a href="{image.popup}" if={getState().setting.global.onclick == 'popup'}><img src="{image.thumb}"/></a>
            <a class="image-popup" href="{getState().setting.global.link}" target={getState().setting.global.link_target=='new' ? '_blank': ''} if={getState().setting.global.onclick == 'link'}><img src="{image.thumb}"/></a>
            <a if={getState().setting.global.onclick != 'link' && getState().setting.global.onclick != 'popup'}><img src="{image.thumb}"/></a>
        </virtual>
    </div>
    
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            slider: null,
            lgallery: null,
        })

        this.on('update', function(){
            var slider = this.getState().slider
            var lgallery = this.getState().lgallery
            if(!_.isNull(slider) && !_.isUndefined(slider.destroy)){
                slider.destroy()
            }
            if(!_.isNull(lgallery) && !_.isUndefined(lgallery.destroy)) {
                lgallery.destroy()
            }
            $(".gallery", this.root).justifiedGallery('destroy')
        })
        this.on('updated', function(){
            this.initGallery()
        })

        this.on('mount', function(){
            this.initGallery()
        })
        this.initGallery = function(){
            var setting = this.getState().setting
            var slider = this.getState().slider
            var lgallery = this.getState().lgallery
            if(_.indexOf(['slider_fade', 'slider_slide'], setting.global.type_gallery) != -1) {
                slider = $('.gallery', this.root).lightSlider({
                    mode : setting.global.type_gallery == 'slider_fade'? 'fade' : 'slide',
                    gallery:true,
                    adaptiveHeight:true,
                    item:1,
                    loop:true,
                    thumbItem:9,
                    slideMargin:0,
                    enableDrag: false,
                    currentPagerPosition:'left',
                    speed: setting.global.auto_rotate,
                    onSliderLoad: function(el) {
                        if(setting.global.onclick == 'popup') {
                            lgallery = el.lightGallery({
                                selector: '.gallery .lslide'
                            });
                        }
                    }.bind(this)
                });
            }
            if(setting.global.type_gallery == 'image_grid') {
                $(".gallery", this.root).justifiedGallery({
                    border: 6
                });
                if(setting.global.onclick == 'popup') {
                    $(".gallery", this.root).on('jg.complete',  function() {
                        lgallery = $(".gallery", this.root).lightGallery({
                            thumbnail:true
                        });
                    }.bind(this));
                }
            }
            this.setState({
                slider: slider,
                lgallery: lgallery
            })
        }
    </script>
</vd-block-gallery>