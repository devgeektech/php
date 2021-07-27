<vd-block-pricing_table_section>
    <svg width="0" height="0">
        <defs>
            <clipPath id="vd-pricing-table-clip-shape" clipPathUnits="objectBoundingBox">
            <polygon points="0.5 1, 0 0, 1 0" />
        </clipPath>
    </defs>
    </svg>
    <div class="vd-pricing-table {getState().setting.global.style}">
        <div class="vd-pricing-table-title">
            <div class="vd-pricing-table-icon" if={getState().setting.global.display_icon}>
                <i class="{getState().setting.global.icon}"></i>
            </div>
            <div class="h3">{getState().setting.global.title}</div>
            <h4 if={getState().setting.global.subtitle}>{getState().setting.global.subtitle}</h4>
            <hr/>
            <div class="vd-price-container" if={getState().setting.global.price}>
                <span class="vd-price-wrapper">
                    <span class="vd-price-curency" if={getState().setting.global.currency}>{getState().setting.global.currency}</span>
                    <span class="vd-price-text">{getState().setting.global.price}</span>
                    <span class="vd-price-per" if={getState().setting.global.per}>{getState().setting.global.per}</span>
                </span>
            </div>
        </div>
        <div class="vd-price-bottom-container"></div>
        <div class="vd-pricing-table-container">
                <div class="vd-pricing-table-content" if={getState().setting.global.feautures}>
                    <div class="vd-pricing-table-feauture" each={value in getState().setting.global.feautures.split(',')}>{value}</div>
                </div>
                <div class="vd-pricing-table-button-container" if={getState().setting.global.display_button}>
                    <a class="vd-pricing-table-button">{getState().setting.global.button_text}</a>
                </div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initStyle();
        })
        this.on('update', function(){
            this.setting = this.opts.block.setting
            this.initStyle();
        })
        this.initStyle = function(){
            var setting = this.getState().setting

            var styles = {
                '.vd-pricing-table > .vd-pricing-table-title > .vd-pricing-table-icon': {
                    'background-color': setting.global.background
                },
                '.vd-pricing-table > .vd-pricing-table-title': {
                    'background-color': setting.global.background
                },
                '.vd-pricing-table > .vd-pricing-table-title > h3': {
                    'background-color': setting.global.background
                },
                '.vd-pricing-table > .vd-pricing-table-title > hr': {
                    'border-top-color': setting.global.background
                },
                '.vd-pricing-table > .vd-pricing-table-title > .vd-pracing-table-separator' :{
                    'background-color': setting.global.background
                },
                '.vd-pricing-table > .vd-pricing-table-container > .vd-pricing-table-button-container' : {
                    'padding-top': setting.global.button_padding_top,
                    'padding-bottom': setting.global.button_padding_bottom
                },
                '.vd-pricing-table-button': {
                    'background': setting.global.button_background,
                    'color': setting.global.button_color_text,
                    'border': setting.global.button_border_width+' solid '+ setting.global.button_border_color,
                    'border-radius': setting.global.button_style=='Rounded'? '6px': '0'
                },
                '.vd-pricing-table > .vd-price-bottom-container:after': {
                    'background-color': setting.global.background
                }
            }
            this.store.dispatch('block/style/update', {designer_id: this.getState().top.opts.id, block_id: this.opts.block.id, styles: styles})
        }
    </script>
</vd-block-pricing_table_section>