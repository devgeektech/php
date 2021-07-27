<vd-block-countdown_flip>
    <div class="flip-countdown">
        <div></div>
    </div> 
    <style>
        .flip-clock-wrapper{
            width: inherit;
            display: inline-block;
            margin: 2em 1em 1em 1em;
        }
    </style>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initCountdown();
        })
        this.on('updated', function(){
            this.initCountdown();
        })
        this.updateStyle = function(){
            var setting = this.getState().setting
            $('.flip-countdown', this.root).css({'text-align': setting.global.position})
            $('.flip-clock-label', this.root).css({'color': setting.global.color_title})
            if(setting.global.display_title) {
                $('.flip-clock-label', this.root).css({'display': ''})
            } else {
                $('.flip-clock-label', this.root).css({'display': 'none'})
            }
            if(setting.global.display_second) {
                $('.flip-clock-divider.seconds', this.root).css({'display': ''})
                $('.flip-clock-divider.seconds ~ ul.flip', this.root).css({'display': ''})
            } else {
                $('.flip-clock-divider.seconds', this.root).css({'display': 'none'})
                $('.flip-clock-divider.seconds ~ ul.flip', this.root).css({'display': 'none'})
            }
            $('.flip-clock-dot', this.root).css({'background': setting.global.color_title})
            $('.flip-clock-wrapper ul li a div div.inn', this.root).css({'color': setting.global.color_number, 'background': setting.global.background})
            $('.flip-clock-wrapper', this.root).css({'zoom': setting.global.scale, '-moz-transform': setting.global.scale})
            setTimeout(function(){
                $('.flip-clock-wrapper ul li a div div.inn', this.root).css({'color': setting.global.color_number, 'background': setting.global.background})
            }.bind(this), 100);
        }
        this.initCountdown = function(){
            var setting = this.getState().setting
            var clock = $('.flip-countdown > div', this.root).FlipClock(setting.user.time, {
                countdown:true,
                clockFace: setting.user.type_countdown,
                showSeconds: setting.global.display_second,

                callbacks: {
                    interval:function() {
                       this.updateStyle()
                    }.bind(this),
                    start:function() {
                       //this.updateStyle()
                    }.bind(this)
                }
            });
            
        }
    </script>
</vd-block-countdown_flip>