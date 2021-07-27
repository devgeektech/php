<vd-block-product>
    <div class="product-container col-md-12 vd-product-style-{getState().setting.global.style}" if={getState().setting.user.product}>
        <div class="vd-product transition">
            <vd-product product={getState().setting.user.product}/>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-product>