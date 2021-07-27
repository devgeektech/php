<vd-block-mailerlite>
    <style scoped>
        :scope  .vd-mailerlite .form-inline{
            text-align: center;
        }
        :scope  .vd-mailerlite .form-inline .form-group{
            margin-left: 5px;
            margin-right: 5px; 
            margin-bottom: 10px;

        }
        :scope  .vd-mailerlite .form-inline .control-label{
            display: none;
        }
        :scope  .vd-mailerlite .text-danger {
            margin-bottom: 15px;
        }
        :scope  .vd-mailerlite .vd-mailerlite-submit{
            text-decoration: none;
            cursor:pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media only screen and (max-device-width:768px){

            :scope  .vd-mailerlite .form-group input{
                text-align:center;
                margin-left: auto;
                margin-right: auto;
            }

            :scope  .vd-mailerlite .form-group a{
                text-align:center;
                margin-left: auto;
                margin-right: auto;
            }
        }
        :scope .vd-mailerlite .vd-mailerlite-submit:hover{
            text-decoration: none;
        }
        :scope .vd-mailerlite {
            color: attr(button_color_text);
        }
    </style>
    <div class="vd-mailerlite" id="{opts.block.id}">
        <div class="text-danger" show={!_.isNull(getState().error)}>{getState().error}</div>
        <div class={getState().setting.global.inline?'form-inline': null} hide={getState().success}>
            <div class="form-group" hide={!getState().setting.display_firstname}>
                <label class="control-label">{store.getLocal('blocks.mailerlite.entry_firstname')}</label>
                <input class="form-control" type="text" name="firstname" value="" placeholder="{store.getLocal('blocks.mailerlite.entry_firstname')}" />
            </div>
            <div class="form-group"  hide={!getState().setting.display_lastname}>
                <label class="control-label">{store.getLocal('blocks.mailerlite.entry_lastname')}</label>
                <input class="form-control" type="text" name="lastname" value="" placeholder="{store.getLocal('blocks.mailerlite.entry_lastname')}" />
            </div>
            <div class="form-group required {!_.isNull(getState().error)? 'has-error': ''}">
                <label class="control-label">{store.getLocal('blocks.mailerlite.entry_email')}</label>
                <input class="form-control" type="text" name="email" value="" placeholder="{store.getLocal('blocks.mailerlite.entry_email')}" />
            </div>
            <div class="form-group ">
                <a onclick={subscribe} class="vd-mailerlite-submit {getState().setting.global.button_style != 'custom' ? 'btn btn-'+setting.button_style : ''}">{getState().setting.global.button_title}</a>
            </div>
        </div>
        <div hide={!getState().success}>
            <div class="alert alert-success">{getState().setting.user.final_text}</div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            error: null
        })

        subscribe(e) {
            var setting = this.getState().setting
            var form = $('.vd-mailerlite', this.root);
            data = {
                api: setting.global.api,
                list_id: setting.global.list_id,
                email: form.find('input[name=email]').val(),
                firstname: form.find('input[name=firstname]').val(),
                lastname: form.find('input[name=lastname]').val()
            }

            $.ajax({
                url: 'index.php?route=extension/d_visual_designer_module/mailerlite/subscribe',
                type: 'POST',
                dataType: 'json',
                data: data,
                context: this,
                success: function(json) {
                    if (json['success']) {
                        this.setState({
                            error: null,
                            success: true
                        })
                        $(document).trigger('subscription_conversion');
                    }
                    if(json['error']) {
                        this.setState({error: json.error})
                    }

                    this.update()
                }
            });
        }.bind(this)

        this.on('mount', function(){
            this.initStyles()
        })
        this.on('updated', function(){
            this.initStyles()
        })
        this.initStyles = function(){
            var setting = this.getState().setting
            if(setting.global.button_style == 'custom'){
                $('.vd-mailerlite-submit', this.root).attr({'button_color_text': setting.global.button_color_text})

                $('.vd-mailerlite-submit', this.root).css({
                    'border-width': setting.global.button_border_width,
                    'border-style': 'solid',
                    'font-size': setting.global.button_font_size.replace('px', '')+'px',
                    'border-radius': setting.global.button_border_radius+'px'
                })
                if(setting.global.button_width) {
                    $('.vd-mailerlite-submit', this.root).css({'width': setting.global.button_width})
                } else {
                    $('.vd-mailerlite-submit', this.root).css({'width': '50px'})
                }
                if(setting.global.button_height) {
                    $('.vd-mailerlite-submit', this.root).css({'height': setting.global.button_height})
                } else {
                    $('.vd-mailerlite-submit', this.root).css({'height': '20px'})
                }
            }

            $('input[type=text]', this.root).css({
                'border-width': setting.global.input_border_width,
                'border-style': 'solid',
                'font-size': setting.global.input_font_size,
                'border-radius': setting.global.input_border_radius,
                'background-color': setting.global.input_background_color,
                'color': setting.global.input_color_text,
            })
            if(setting.global.input_width) {
                $('input[type=text]', this.root).css({'width': setting.global.input_width})
            } else {
                $('input[type=text]', this.root).css({'width': '50px'})
            }
            if(setting.global.input_height) {
                $('input[type=text]', this.root).css({'height': setting.global.input_height})
            } else {
                $('input[type=text]', this.root).css({'height': '20px'})
            }
            
            var style = {
                'input[type=text]::-webkit-input-placeholder': {
                    'color': setting.global.input_color_text,
                },
                'input[type=text]:-ms-input-placeholder': {
                    'color': setting.global.input_color_text,
                },
                'input[type=text]:-moz-placeholder': {
                    'color': setting.global.input_color_text,
                },
                '.form-group:not(.has-error) input[type=text]:focus': {
                    'border-color': setting.global.input_focus_border_color,
                    'box-shadow': 'none',
                },
                '.form-group:not(.has-error) input[type=text]': {
                    'border-color': setting.global.input_border_color
                },
                '.vd-mailerlite-submit': {
                    'background-color': setting.global.button_background_color,
                    'color': setting.global.button_color_text,
                    'border-color':setting.global.button_border_color
                },
                '.vd-mailerlite-submit:hover': {
                    'background-color': setting.global.button_hover_background_color,
                    'color': setting.global.button_hover_color_text,
                    'border-color': setting.global.button_hover_border_color
                }
            }

            this.renderStyle(style)
        }

        this.renderStyle = function(styles) {
            $('body').find('style#'+this.opts.block.id).remove();

            var styleTag = '<style id="'+this.opts.block.id+'">';

            for(var key in styles) {
                styleTag += '#'+this.opts.block.id+' '+key+'{';
                for(var name in styles[key]){
                    styleTag += name + ':'+styles[key][name]+';'
                }
                styleTag += '}';
            }
            styleTag += '<style>'
            $('body').append(styleTag);
        }
    </script>
</vd-block-mailerlite>