<vd-block-countdown>
    <div class="coundown"></div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.iniCountdown()
        })
        this.on('updated', function(){
            this.iniCountdown()
        })
        this.iniCountdown = function() {
            var setting = this.getState().setting
            $('.coundown', this.root).countdown(setting.global.datetime).on('update.countdown', function(event) {
                $('.coundown', this.root).html(event.strftime(setting.user.text));
            }.bind(this)).on('finish.countdown', function(event) {
                $('.coundown', this.root).html(setting.user.final_text).parent().addClass('disabled');;
            }.bind(this));
        }
    </script>
</vd-block-countdown>