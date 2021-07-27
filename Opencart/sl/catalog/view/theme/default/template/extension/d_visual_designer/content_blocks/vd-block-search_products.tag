<vd-block-search_products>
    <div class="h2"if={getState().setting.global.title}>{getState().setting.global.title}</div>

    <div class="input-group vd-search">
        <input type="text" name="vd_search" value="" placeholder="{store.getLocal('blocks.search_products.text_search')}" class="form-control input-lg" />
        <span class="input-group-btn">
            <button onclick={search} type="button" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
        </span>
    </div>
    <script>
        this.mixin(new vd_block(this))
        search(e) {
            var url = $('base').attr('href') + 'index.php?route=product/search';

            var value = $('input[name=\'vd_search\']', this.root).val();

            if (value) {
                url += '&search=' + encodeURIComponent(value);
            }

            location = url;
        }
        this.on('mount', function(){
            $('input[name=\'vd_search\']', this.root).on('keypress', function (e) {
                if (e.which === 13) {
                    var url = $('base').attr('href') + 'index.php?route=product/search';
                    var value = $(this).val();
                    if (value) {
                        url += '&search=' + encodeURIComponent(value);
                    }
                    location = url;
                }
            })
        })
    </script>
</vd-block-search_products>