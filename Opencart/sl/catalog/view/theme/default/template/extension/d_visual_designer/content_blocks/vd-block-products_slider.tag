<vd-block-products_slider>
    <div class="h3" if={getState().setting.global.title}>{getState().setting.global.title}</div>

    <div class="product-slider">
        <div class="product-layout col-sm-12" each={product in getState().setting.user.products}>
            <div class="vd-product-thumb transition">
                <vd-product product={product}/>
            </div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initSlider();
        })
        this.on('update', function(){
            var owl = $('.product-slider', this.root).data('owlCarousel')
            if(!_.isUndefined(owl)) {
                owl.destroy()
            }
        })
        this.on('updated', function(){
            this.initSlider();
        })
        this.initSlider = function(){
            var setting = this.getState().setting
            $('.product-slider', this.root).owlCarousel({
                items: setting.global.count,
                autoPlay: setting.global.interval,
                singleItem: setting.global.count == 1,
                navigation: true,
                navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
                pagination: true
            });
        }
    </script>
</vd-block-products_slider>