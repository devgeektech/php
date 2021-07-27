<vd-block-conversion>
    <virtual if={store.getState().config.permission[getState().top.opts.id]}>
        <div class="text-center h3" if={getState().setting.global.title}>{getState().setting.global.title}</div>
        <div class="conversion-container">
            <div class="vd-conversion">
                <div class="viewed">
                    <span class="title">{store.getLocal('blocks.conversion.text_viewed')}</span>
                    <span class="count" >{getState().setting.user.view}</span>
                </div>
                <div class="conversions">
                    <span class="title">{store.getLocal('blocks.conversion.text_conversions')}</span>
                    <span class="count" >{getState().setting.user.conversion}</span>
                </div>
                <div class="conversion-percentage">
                    <span class="title">{store.getLocal('blocks.conversion.text_conversion_percentage')}</span>
                    <span class="count" >{getState().setting.user.conversion_percentage}</span>
                </div>
            </div>
        </div>
    </virtual>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            if(conversion == undefined){
                var conversion = conversion_base||{};
            }
            var setting = this.getState().setting
            conversion.init(setting.global.description_id);
            $(document).on('click', setting.user.selector_link? setting.user.selector_link: 'a', function(){
                conversion.addConversion(setting.global.description_id, 'link');
            }.bind(this));
            $(document).on('click', '[onclick*=\'cart.add\']', function(e){
                if(this.setting.global.type_conversion == 'buy') {
                    conversion.addConversion(setting.global.description_id, 'cart');
                }

                var onclick = $(e.currentTarget).attr('onclick');
                var cartRegex = /cart\.add\(\s?\'([0-9]*)\'\s?(,\s?\'[0-9]*\')?\s?\)/g;
                var match = cartRegex.exec(onclick);
                var product_id = match[1];

                conversion.addConversionBuy(setting.global.description_id, product_id);
            }.bind(this));
            $(document).ready(function(){
                conversion.addConversion(setting.global.description_id, 'view');
            }.bind(this));
            $(document).on('subscription_conversion', function(){
                conversion.addConversion(setting.global.description_id, 'subscription');
            }.bind(this))
            $(document).on('feedback_conversion', function(){
                conversion.addConversion(setting.global.description_id, 'feedback');
            }.bind(this))
        })
    </script>
</vd-block-conversion>