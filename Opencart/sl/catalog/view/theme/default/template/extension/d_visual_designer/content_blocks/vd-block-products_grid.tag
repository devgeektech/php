<vd-block-products_grid>
    <div class="h3" if={getState().setting.global.title}>{getState().setting.global.title}</div>

    <div class="row">
        <div class="product-layout col-md-{getState().setting.global.width} col-sm-{getState().setting.global.width_tablet} col-{getState().setting.global.width_phone}" each={product in getState().setting.user.products}>
            <div class="vd-product-thumb transition">
                <vd-product product={product}/>
            </div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-products_grid>