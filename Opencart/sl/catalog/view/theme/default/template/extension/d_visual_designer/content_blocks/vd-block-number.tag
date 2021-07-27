<vd-block-number>
    <div class="vd-number">0</div>
    <script>
        this.mixin(new vd_block(this))
        this.on('update', function(){
            this.initNumber()
        })
        this.on('mount', function(){
            this.initNumber()
        })
        this.initNumber = function(){
            var setting = this.getState().setting
            if(setting.global.font_size) {
                $(".vd-number", this.root).css({'font-size': setting.global.font_size, 'line-height': setting.global.font_size})
            } else {
                $(".vd-number", this.root).css({'font-size': '', 'line-height': ''})
            }
            if(setting.global.bold) {
                $(".vd-number", this.root).css({'font-weight': 'bold'})
            } else {
                $(".vd-number", this.root).css({'font-weight': ''})
            }
            if(setting.global.color) {
                $(".vd-number", this.root).css({'color': setting.global.color})
            } else {
                $(".vd-number", this.root).css({'color': ''})
            }
            var top = $(".vd-number", this.root).offset().top;
            if($(window).scrollTop()+$(window).height() < top) {
                $(window).scroll(function() {
                    var setting = this.getState().setting
                    if ($(window).scrollTop() + $(window).height() >= top  && $(".vd-number", this.root).size() !== 0) {
                        $(".vd-number", this.root).spincrement({
                            to: Number(setting.global.number),
                            duration: Number(setting.global.duration),
                            thousandSeparator: setting.global.thousand_separator
                        })
                    }
                }.bind(this))
            } else {
                $(".vd-number", this.root).spincrement({
                    to: Number(setting.global.number),
                    duration: Number(setting.global.duration),
                    thousandSeparator: setting.global.thousand_separator
                })
            }
        }
    </script>
</vd-block-number>