<vd-block-accordion>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-accordion>
<vd-block-button>
    <div class="button vd-button-container vd-button-align-{getState().setting.global.alignment}">
        <button onclick="{getState().setting.user.action}" class="vd-button {getState().setting.global.display_icon?'vd-button-icon':''} {getState().setting.global.display_icon_hover?'vd-button-icon-hover':''}">
            <span class="{getState().setting.global.icon} left" if={getState().setting.global.icon_align == 'left'}></span>
            <span class="title">{getState().setting.global.text}</span>
            <span class="{getState().setting.global.icon} right" if={getState().setting.global.icon_align == 'right'}></span>
        </button>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('update', function(){
            this.initButton()
        })

        this.initButton = function(){
            var setting = this.getState().setting
            if(setting.global.font_family != 'default') {
                WebFont.load({
                    google: {
                        families: [setting.global.font_family, setting.global.font_family+' i7']
                    }
                });
            }
            $('.vd-button', this.root).css({
                'background-color': setting.global.color,
                'color': setting.global.color_text,
                'border-width': setting.global.border_width,
                'border-style': 'solid',
                'border-color': setting.global.border_color,
                'border-radius': setting.global.border_radius,
                'font-size': setting.global.font_size,
                'letter-spacing': setting.global.letter_spacing
                });

            $('.vd-button', this.root).hover(function(){
                $('.vd-button', this.root).css({
                  'background-color': setting.global.color_hover,
                  'border-color': setting.global.border_color_hover,
                  'color': setting.global.color_text_hover
                })
            }.bind(this), function(){
                 $('.vd-button', this.root).css({
                  'background-color': setting.global.color,
                  'border-color': setting.global.border_color,
                  'color': setting.global.color_text
                })
            }.bind(this))
            if(setting.global.font_family != 'default') {
                $('.vd-button', this.root).css({'font-family': setting.global.font_family});
            } else {
                $('.vd-button', this.root).css({'font-family': ''});
            }
            if(setting.global.bold) {
                $('.vd-button', this.root).css({'font-weight': '700'});
            } else {
                $('.vd-button', this.root).css({'font-weight': ''});
            }
            if(setting.global.italic) {
                $('.vd-button', this.root).css({'font-style': 'italic'});
            } else {
                $('.vd-button', this.root).css({'font-style': ''});
            }
            if(setting.global.underline) {
                $('.vd-button', this.root).css({'text-decoration': 'underline'});
            } else {
                $('.vd-button', this.root).css({'text-decoration': ''});
            }
            if(setting.global.full_width) {
                $('.vd-button', this.root).css({'width': '100%'});
            } else if(setting.global.width) {
                $('.vd-button', this.root).css({'width': setting.global.width});
            } else {
                $('.vd-button', this.root).css({'width': ''});
            }
            if(setting.global.height) {
                $('.vd-button', this.root).css({'height': setting.global.height});
            } else {
                $('.vd-button', this.root).css({'height': ''});
            }
            $('span:not(.title)', this.root).css({'color': setting.global.icon_color})
        }
        this.on('mount', function(){
            this.initButton()
        })

    </script>
</vd-block-button>
<vd-block-circle_bar>
    <div class="h2" if={getState().setting.title}>{getState().setting.global.title}</div>
    <div class="pie" data-percent="{getState().setting.global.value}">
        <span class="pie-value" if={getState().setting.global.label_value}>{getState().setting.global.label_value} {getState().setting.global.units}</span>
        <span class="pie-value" if={!getState().setting.global.label_value}>{getState().setting.global.value} {getState().setting.global.units}</span>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            pieChart: null
        })
        this.on('update', function(){
            this.initCircleBar()
        })
        this.on('mount', function(){
            this.initCircleBar()
        })
        this.initCircleBar = function(){
            var pieChart = this.getState().pieChart
            var block_setting = this.getState().setting
            var setting = {};
            if(block_setting.global.bar_color) {
                setting['barColor'] = block_setting.global.bar_color;
            }
            if(block_setting.global.track_color) {
                setting['trackColor'] = block_setting.global.track_color;
            }
            if(block_setting.global.line_width) {
                setting['lineWidth'] = block_setting.global.line_width.replace('px', '');
            }
            if(block_setting.global.size) {
                setting['size'] = block_setting.global.size.replace('px', '');
            }
            if(!_.isNull(pieChart)){
                pieChart.removeData('pieChart')
                $('.pie canvas', this.root).remove()
            }
            this.setState({pieChart: $('.pie', this.root).pieChart(setting)})
        }
    </script>
</vd-block-circle_bar>
<vd-block-code>
    <raw html={getState().setting.global.code}/>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-code>
<vd-block-collage>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <div class="vd-collage vd-collage-type-{getState().setting.global.type_collage}">
        <virtual each={subImages in getState().images}>
            <div class="vd-collage-{_.size(subImages.images) > 1 ? 'small' : 'big'}" style="{subImages.width_calc? 'width:' + subImages.width_calc + ';': ''}">
                <virtual each={image in subImages.images}>
                    <a href={image.href} target={image.target}>
                        <img src="{image.thumb}"/>
                    </a>
                    <div class="vd-collage-horizontal-border" style="height:{image.border_width};"></div>
                </virtual>
            </div>
            <div class="vd-collage-border" style="width:{subImages.border_width};"></div>
        </virtual>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            images: []
        })

        this.initImage = function() {
            var setting = this.getState().setting
            var border_width = Number(String(setting.global.border_width).replace('px',''))
            if(!_.isEmpty(setting.user.images)){
                var schema = setting.global.scheme.split('-')
                var links = setting.global.link.split(';')
                var usedLinks = 0
                schema = _.map(schema, Number);
                var images = []
                var countImages = 0;

                for (var key in schema) {
                    var subImages = {
                        images: _.first(_.rest(_.toArray(setting.user.images), countImages), schema[key])
                    }

                    subImages.images = _.map(subImages.images, function(image, image_key){
                         if(setting.global.onclick == 'popup') {
                            image.href = image.popup
                            image.target = null
                        } else if(setting.global.onclick == 'link') {
                            image.href = !_.isUndefined(links[usedLinks])? links[usedLinks]: links[_.last(_.keys(links))]
                            image.target = setting.global.link_target=='new' ? '_blank': ''
                        } else {
                            image.href = null
                            image.target = null
                        }

                        if(image_key != _.last(_.keys(subImages.images))){
                            image.border_width = setting.global.border_width
                        } else {
                            image.border_width = '0'
                            }

                        usedLinks++;
                        return image
                    }.bind(this))
  
                    subImages.ratio = 100 / _.reduce(_.map(subImages.images, function(image) { 
                        return 100 * image.height / image.width
                    }), function(a, b) { return a+b}, 0)
                    subImages.count = _.size(subImages.images) - 1

                    images.push(subImages)
                    countImages += schema[key]
                }
                this.setState('images', images)
            } else {
                this.setState('images', {})
            }
        }

        this.round = function(value, decimals) {
            return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
        }

        this.recalculateWidth = function() {
            var setting = this.getState().setting
            var images = this.getState().images
            var border_width = Number(String(setting.global.border_width).replace('px',''))

            var sumRatio = _.reduce(images, function(a, b) {return a+b.ratio}, 0);

            var horizAddon = _.map(images, function(image) {
                return image.ratio*image.count
            }).reduce(function(a, b) {return a+b}, 0) + (_.size(images) - 1);

            var clientWidth = $('.vd-collage', this.root).width()//- ((_.size(this.images) - 1)*border_width)

            var height = (clientWidth + border_width*horizAddon) / sumRatio 

            var widths = []
            for (var key in images) {
  
                var calcHeight = height - images[key].count * border_width

                var width_calc =  images[key].ratio * calcHeight / clientWidth*100

                var border_width_calc = (_.size(images) - 1) * border_width / 100 * width_calc

                images[key].width_calc = 'calc(' + this.round(width_calc, 2) + '% - ' + border_width_calc + 'px)'

                if(key != _.last(_.keys(images))){
                    images[key].border_width = setting.global.border_width
                } else {
                    images[key].border_width = '0'
                }
            }
            this.setState({images: images})
        }

        this.initCollage = function(){
            var setting = this.getState().setting
            if(setting.global.onclick == 'popup'){
                $('.vd-collage', this.root).magnificPopup({
                    type:'image',
                    delegate: 'a',
                    gallery: {
                        enabled:true
                    }
                });
            }
        }

        this.on('update', function(){
            this.initImage()
            this.initCollage()
            this.recalculateWidth()
        })

        this.on('mount', function(){
            this.recalculateWidth()
            this.initCollage()
            this.update()
        })


        $(window).on('resize', function(){
            var setting = this.getState().setting
            var border_width = Number(String(setting.global.border_width).replace('px',''))
            if(border_width > 0) {
                this.recalculateWidth()
                this.update()
            }
        }.bind(this))

        this.initImage()
    </script>
</vd-block-collage>
<vd-block-column>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))

    </script>
</vd-block-column>
<vd-block-column_inner>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-column_inner>
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
<vd-block-custom_heading>
    <div class="vd-custom-heading vd-custom-heading-align-{getState().setting.global.align}">
        <a href={getState().setting.global.link} if={getState().setting.link}>
            <raw html={'<'+getState().setting.global.tag+'>'+getState().setting.user.text+'</'+getState().setting.global.tag+'>'}/>
        </a>
        <div if={!getState().setting.global.link}><raw html={'<'+getState().setting.global.tag+'>'+getState().setting.user.text+'</'+getState().setting.global.tag+'>'}/></div>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('update', function(){
            this.initCustomHeading()
        })
        this.on('updated', function(){
            this.initCustomHeading()
        })
        this.on('mount', function(){
            this.initCustomHeading()
        })
        this.initCustomHeading = function(){
            var setting = this.getState().setting
            if(setting.global.font_family != 'default') {
                WebFont.load({
                    google: {
                        families: [setting.global.font_family, setting.global.font_family+' i7']
                    }
                });
            }
            var element = $('.vd-custom-heading', this.root)
            element.css({'color': setting.global.color})
            if(setting.global.font_family) {
                element.css({'font-family': setting.global.font_family}) 
            } else {
                element.css({'font-family': ''}) 
            }
            if(setting.global.bold) {
                element.css({'font-weight': '700'});
            } else {
                element.css({'font-weight': ''});
            }
            if(setting.global.italic) {
                element.css({'font-style': 'italic'});
            } else {
                element.css({'font-style': ''});
            }
            if(setting.global.underline) {
                element.css({'text-decoration': 'underline'});
            } else {
                element.css({'text-decoration': ''});
            }
            if(setting.global.line_height) {
                element.css({'line-height': setting.global.line_height});
            } else {
                element.css({'line-height': '100%'});
            }
            if(setting.global.font_size && _.indexOf(['div', 'p'], setting.global.tag) != -1) {
                element.css({'font-size': setting.global.font_size});
            } else {
                element.css({'font-size': ''});
            }
        }
        this.initCustomHeading()
    </script>
</vd-block-custom_heading>
<vd-block-empty_space>
    <div style="height:{getState().setting.global.height}"></div>
    <script>
    this.mixin(new vd_block(this))
    </script>
</vd-block-empty_space>
<vd-block-facebook_like>
    <div class="vd-facebook-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
       
        this.on('mount', function(){
            this.initFacebookLike();
        })
        this.on('update', function(){
            this.initFacebookLike();
        })
        this.initFacebookLike = function(){
            var setting = this.getState().setting
            $('.vd-facebook-like', this.root).empty()
            $('.vd-facebook-like', this.root).sharrre({
                share: {
                    facebook: true,
                },
                buttons: {
                    facebook: {
                        layout: setting.global.type_button,
                        count: 'horizontal'
                    },
                },
                enableHover: false,
                enableCounter: false,
                enableTracking: true
            });
        }
    </script>
</vd-block-facebook_like>
<vd-block-facebook_widget>
    <iframe class="fb-page" src="//www.facebook.com/plugins/page.php?{getState().url}&appId" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            url: ''
        })
        this.on('update', function(){
            this.initFacebook()
        })

        this.on('updated', function(){
            this.initStyle();
        })

        this.on('mount', function(){
            this.initStyle();
        })

        this.initFacebook = function(){
            var setting = this.getState().setting

            var url = [];
            url.push('href='+encodeURIComponent(setting.global.href))
            url.push('tabs='+_.values(setting.global.tabs).join(','))
            url.push('small_header='+setting.global.small_header)
            url.push('adapt_container_width=true')
            url.push('hide_cover=false')
            url.push('show_facepile='+setting.global.show_facepile)

            if(setting.global.height) {
                url.push('height='+setting.global.height)
            }
            if(setting.global.width) {
                 url.push('width='+setting.global.width)
            }
            this.setState({url: url.join('&')})
        }

        this.initStyle = function(){
            var setting = this.getState().setting

            if(setting.global.height) {
                $('.fb-page', this.root).css({'height': setting.global.height})
            } else {
                $('.fb-page', this.root).css({'height': ''})
            }
            if(setting.global.width) {
                $('.fb-page', this.root).css({'width': setting.global.width})
            } else {
                $('.fb-page', this.root).css({'width': ''})
            }
        }

        this.initFacebook();
    </script>
</vd-block-facebook_widget>
<vd-block-features>
    <div class="vd-features-container image-style-{getState().setting.global.image_position}">
        <div class="image vd-image-size-{getState().setting.global.size}">
            <img src="{getState().setting.user.thumb}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
            <div class="title h3" if={getState().setting.global.image_position == 'left_top'}>{getState().setting.global.title}</div>
        </div>
        <div class="text-container" if={getState().setting.global.image_position == 'left'}>
            <div class="title h3" if={getState().setting.global.image_position != 'left_top'}>{getState().setting.global.title}</div>
            <div class="text"><raw html="{getState().setting.global.text}"/></div>
        </div>
        <div if={getState().setting.global.image_position != 'left'}>
            <div class="title h3" if={getState().setting.global.image_position != 'left_top'}>{getState().setting.global.title}</div>
            <div class="text"><raw html="{getState().setting.global.text}"/></div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))

        this.initStyle = function() {
           var setting = this.getState().setting

            if(setting.global.size == 'custom') {
                $('.image > img',this.root).css({width: setting.global.width, height: setting.global.height})
            } else {
                $('.image > img',this.root).css({width: '', height: ''})
            }
        }

        this.on('mount', function () {
            this.initStyle()
        })

        this.on('updated', function () {
            this.initStyle()
        })
    </script>
</vd-block-features>
<vd-block-feedback>
    <div if={!success}>
        <div class="form-group required {!_.isEmpty(getState().errors.name)? 'has-error': ''}">
            <label class="control-label">{store.getLocal('blocks.feedback.entry_name')}</label>
            <input class="form-control" type="text" name="name" value="" data-name="" data-validate="" />
            <div class="text-danger" if={!_.isEmpty(getState().errors.name)}>{getState().errors.name}</div>
        </div>
        <div class="form-group required {!_.isEmpty(getState().errors.email)? 'has-error': ''}">
            <label class="control-label">{store.getLocal('blocks.feedback.entry_email')}</label>
            <input class="form-control" type="text" name="email" value="" data-name="" data-validate="" />
            <div class="text-danger" if={!_.isEmpty(getState().errors.email)}>{getState().errors.email}</div>
        </div>
        <div class="form-group" each={field, key in getState().setting.global.fields}>
            <label class="control-label">{field.name[store.getOptions('blocks.feedback.language_id')]}</label>
            <input class="form-control" type="text" name="fields[{key}][value]" value="{field.value}" />
            <input type="hidden" name="fields[{key}][name]" value="{field.name}" />
        </div>
        <div class="form-group required {!_.isEmpty(getState().errors.comment)? 'has-error': ''}">
            <label class="control-label">{store.getLocal('blocks.feedback.entry_comment')}</label>
            <textarea class="form-control" name="comment" data-name="" data-validate=""></textarea>
            <div class="text-danger" if={!_.isEmpty(getState().errors.comment)}>{getState().errors.comment}</div>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-primary button-send" onclick={send}>{store.getLocal('blocks.feedback.button_send')}</button>
        </div>
    </div>
    <div id="success-send" if={getState().success}>
        <raw html={getState().setting.user.code} if={getState().setting.user.code && getState().setting.global.mode == 'code'}/>
        <a if={getState().setting.global.mode == 'download_file'} class="btn btn-primary download-link" href="{getState().setting.global.link}" download>{store.getLocal('blocks.feedback.text_click_download')}</a>
        
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            success: false,
            errors: {
                name: '',
                email: '',
                comment: ''
            }
        })
        send(e) {
            $.ajax({
            url: 'index.php?route=extension/d_visual_designer_module/feedback/send',
            type: 'POST',
            dataType: 'json',
            context: this,
            data: $('input, textarea', this.root).serialize(),
            success: function(json) {
                if (json['success']) {
                    this.success = true
                    this.setState({
                        success: true,
                        errors: {
                            name: '',
                            email: '',
                            comment: ''
                        }
                    })
                }
                if (json['error']) {
                    var errors = {
                        name: '',
                        email: '',
                        comment: ''
                    } 
                    for (var key in json['errors']) {
                        errors[key] = json['errors'][key]
                    }
                    this.setState({errors: errors})
                }
                this.update();
            }
        });
        }
    </script>
</vd-block-feedback>
<vd-block-file>
    <a href="{store.getOptions('blocks.file.download_url')}&download_id={getState().setting.global.download_id}"><span class="{getState().setting.global.icon}"></span> {getState().setting.global.title}</a>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function () {
            this.initStyle()
        })
        this.on('updated', function () {
            this.initStyle()
        })
        this.initStyle = function () {
            var setting = this.getState().setting
            $('a', this.root).css({
                'color': setting.global.color
            })
            $('span', this.root).css({
                'font-size': setting.global.icon_size
            })
        }
    </script>
</vd-block-file>
<vd-block-gallery>
    <style>
        .gallery > li > img {
            height: auto;
            max-width: 100%;
            display: block;
            vertical-align: middle;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <ul class="gallery" if={_.indexOf(['slider_fade', 'slider_slide'], getState().setting.global.type_gallery) != -1}>
        <li each={image in getState().setting.user.images} data-thumb="{image.thumb}" data-src="{image.popup}">
            <img src="{image.thumb}"/>
        </li>
    </ul>
    <div class="gallery" if={getState().setting.global.type_gallery == 'image_grid'}>
        <virtual each={image in getState().setting.user.images}>
            <a href="{image.popup}" if={getState().setting.global.onclick == 'popup'}><img src="{image.thumb}"/></a>
            <a class="image-popup" href="{getState().setting.global.link}" target={getState().setting.global.link_target=='new' ? '_blank': ''} if={getState().setting.global.onclick == 'link'}><img src="{image.thumb}"/></a>
            <a if={getState().setting.global.onclick != 'link' && getState().setting.global.onclick != 'popup'}><img src="{image.thumb}"/></a>
        </virtual>
    </div>
    
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            slider: null,
            lgallery: null,
        })

        this.on('update', function(){
            var slider = this.getState().slider
            var lgallery = this.getState().lgallery
            if(!_.isNull(slider) && !_.isUndefined(slider.destroy)){
                slider.destroy()
            }
            if(!_.isNull(lgallery) && !_.isUndefined(lgallery.destroy)) {
                lgallery.destroy()
            }
            $(".gallery", this.root).justifiedGallery('destroy')
        })
        this.on('updated', function(){
            this.initGallery()
        })

        this.on('mount', function(){
            this.initGallery()
        })
        this.initGallery = function(){
            var setting = this.getState().setting
            var slider = this.getState().slider
            var lgallery = this.getState().lgallery
            if(_.indexOf(['slider_fade', 'slider_slide'], setting.global.type_gallery) != -1) {
                slider = $('.gallery', this.root).lightSlider({
                    mode : setting.global.type_gallery == 'slider_fade'? 'fade' : 'slide',
                    gallery:true,
                    adaptiveHeight:true,
                    item:1,
                    loop:true,
                    thumbItem:9,
                    slideMargin:0,
                    enableDrag: false,
                    currentPagerPosition:'left',
                    speed: setting.global.auto_rotate,
                    onSliderLoad: function(el) {
                        if(setting.global.onclick == 'popup') {
                            lgallery = el.lightGallery({
                                selector: '.gallery .lslide'
                            });
                        }
                    }.bind(this)
                });
            }
            if(setting.global.type_gallery == 'image_grid') {
                $(".gallery", this.root).justifiedGallery({
                    border: 6
                });
                if(setting.global.onclick == 'popup') {
                    $(".gallery", this.root).on('jg.complete',  function() {
                        lgallery = $(".gallery", this.root).lightGallery({
                            thumbnail:true
                        });
                    }.bind(this));
                }
            }
            this.setState({
                slider: slider,
                lgallery: lgallery
            })
        }
    </script>
</vd-block-gallery>
<vd-block-gmaps>
    <div style="{getState().setting.global.height?'height:'+getState().setting.global.height+';':''}">
    <raw html={getState().setting.global.link}/>
    <style>
        raw > div {
            height: 100%;
        }
    </style>
</div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-gmaps>
<vd-block-google_plus_like>
    <div class="vd-google-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initGoogleLike();
        })
        this.on('update', function(){
            this.initGoogleLike();
        })
        this.initGoogleLike = function(){
            var setting = this.getState().setting
            $('.vd-google-like', this.root).empty()
            $('.vd-google-like', this.root).sharrre({
                 share: {
                    googlePlus: true,
                },
                buttons: {
                    googlePlus: {
                        size: setting.global.size,
                        annotation:'bubble'
                    },
                }
            });
        }
    </script>
</vd-block-google_plus_like>
<vd-block-google_plus_widget>
    <div style="text-align: center;" class="widget"></div>
    <script>
        this.mixin(new vd_block(this))
        this.initGoogle = function(){
            var setting = this.getState().setting
            window.___gcfg = {lang: 'ru', parsetags: 'explicit'};
            var element = document.createElement('div')
            element.setAttribute('data-href', setting.global.href);
            element.setAttribute('data-theme', setting.global.theme);
            element.setAttribute('data-layout', setting.global.layout);
            element.setAttribute('data-showtagline', setting.global.showtagline);
            element.setAttribute('class', 'g-' + setting.global.badge_type);

            $('.widget', this.root).html(element);
            gapi[setting.global.badge_type].go()
        }
        this.on('mount', function(){
            this.initGoogle()
        })
        this.on('update', function(){
            this.initGoogle()
        })
    </script>
</vd-block-google_plus_widget>
<vd-block-icon>
    <div class="container-fluid vd-icon-container vd-icon-align-{getState().setting.global.align} {getState().setting.global.align_phone ? 'vd-icon-align-phone-' + setting.global.align_phone: '' } {getState().setting.global.align_tablet ? 'vd-icon-align-tablet-' + getState().setting.global.align_tablet : '' }">
        <a href="{getState().setting.global.link}" style="text-decoration: none;" if={getState().setting.global.link}>
            <div class="vd-icon vd-icon-block vd-icon-{getState().setting.global.size} {getState().setting.global.size_phone ? 'vd-icon-phone-' + getState().setting.global.size_phone : ''} {getState().setting.global.size_tablet ? 'vd-icon-tablet-' + getState().setting.global.size_tablet : ''} vd-icon-style-{getState().setting.global.background_style}">
                <span class="{getState().setting.global.icon}" style="color:{getState().setting.global.color};"></span>
            </div>
        </a>
        <div if={!getState().setting.global.link} class="vd-icon vd-icon-block vd-icon-{getState().setting.global.size} {getState().setting.global.size_phone ? 'vd-icon-phone-' + getState().setting.global.size_phone : ''} {getState().setting.global.size_tablet ? 'vd-icon-tablet-' + getState().setting.global.size_tablet : ''} vd-icon-style-{getState().setting.global.background_style}">
            <span class="{getState().setting.global.icon}" style="color:{getState().setting.global.color};"></span>
        </div>
</div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initIcon()
        })
        this.on('update', function(){
            this.initIcon()
        })
        this.initIcon = function(){
            var setting = this.getState().setting
            $('.vd-icon-style-rounded', this.root).css({'background': setting.global.background_color})
            $('.vd-icon-style-boxed', this.root).css({'background': setting.global.background_color})
            $('.vd-icon-style-rounded-less', this.root).css({'background': setting.global.background_color})
            $('.vd-icon-style-rounded-outline', this.root).css({'border-color': setting.global.background_color})
            $('.vd-icon-style-boxed-outline', this.root).css({'border-color': setting.global.background_color})
            $('.vd-icon-style-rounded-less-outline', this.root).css({'border-color': setting.global.background_color})
        }
    </script>
</vd-block-icon>
<vd-block-image>
    <div class="vd-image-container vd-image-align-{getState().setting.global.align} {getState().classContainer}">
        <div class="vd-image-title" if={getState().setting.global.title}>
            <h2>{getState().setting.global.title}</h2>
        </div>
        <div class="vd-image-wrapper vd-image-size-{getState().setting.global.size} {getState().classWrapper}">
            <div class="vd-image {getState().setting.global.style ? 'vd-image-style-' + getState().setting.global.style : ''}">
                <a>
                    <virtual if={getState().setting.global.parallax == '1'}>
                        <div class="parallax-window" style="{getState().parallaxStyles}"></div>
                    </virtual>
                    <virtual if={getState().setting.global.parallax == '0'}>
                        <img src="{getState().setting.user.thumb}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
                    </virtual>
                </a>
            </div>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            parallaxStyles: '',
            classContainer: '',
            classWrapper: '',
        })
        this.initImage = function (){
            var parallaxStyles = []
            var setting = this.getState().setting
            if(setting.global.parallax == '1'){
                parallaxStyles.push('background-image: url(\''+setting.user.thumb+'\');');
                parallaxStyles.push('height:'+setting.global.parallax_height+';');
                if(setting.global.size != 'responsive') {
                    $('.parallax-window', this.root).css({
                        'width': setting.user.desktop_size.width,
                        'height': setting.user.desktop_size.height,
                    })
                }
            }
            this.setState({parallaxStyles: parallaxStyles.join(' ')})
            if(setting.global.onclick == 'popup'){
                $('.vd-image', this.root).magnificPopup({
                    type:'image',
                    delegate: 'a',
                    gallery: {
                        enabled:true
                    }
                });
                $('.vd-image > a', this.root).attr('class', 'image-popup')
                $('.vd-image > a', this.root).attr('href', setting.user.popup)
            }
            if(setting.global.onclick == 'link'){
                if(setting.global.link_target == 'new'){
                    $('.vd-image > a', this.root).attr('target', '_blank')
                }
                $('.vd-image > a', this.root).attr('href', setting.global.link)
            }
            $('.vd-image img', this.root).css({width: '', height: ''})
            var styles = {
                'phone': {
                    '.vd-image-size-phone-custom img': {
                        'width': setting.global.width_phone,
                        'height': setting.global.height_phone,
                    }
                },
                'tablet': {
                    '.vd-image-size-tablet-custom img': {
                        'width': setting.global.width_tablet,
                        'height': setting.global.height_tablet,
                    }
                },
                'desktop': {
                    '.vd-image-size-custom img': {
                        'width': setting.global.width,
                        'height': setting.global.height,
                    }
                },
            }
            this.store.dispatch('block/style/media/update', {designer_id: this.getState().top.opts.id, block_id: this.opts.block.id, styles: styles})
        }
        this.initClassContainer = function(){
            var classContainer = []
            var setting = this.getState().setting

            if(setting.global.align_phone){
                classContainer.push('vd-image-align-phone-' + setting.global.align_phone)
            }
            if(setting.global.align_tablet){
                classContainer.push('vd-image-align-tablet-' + setting.global.align_tablet)
            }
            this.setState({classContainer: classContainer.join(' ')})
        }.bind(this)
        this.initClassWrapper = function(){
            var classWrapper = []
            var setting = this.getState().setting

            if(setting.global.size_phone){
                classWrapper.push('vd-image-size-phone-'+setting.global.size_phone)
            } else if(setting.global.size_tablet) {
                classWrapper.push('vd-image-size-phone-'+setting.global.size_tablet)
            } else {
                classWrapper.push('vd-image-size-phone-'+setting.global.size)
            }
            if(setting.global.size_tablet){
                classWrapper.push('vd-image-size-tablet-'+setting.global.size_tablet)
            } else {
                classWrapper.push('vd-image-size-tablet-'+setting.global.size)
            }
            this.setState({classWrapper: classWrapper.join(' ')})
        }.bind(this)

        this.initClassContainer()
        this.initClassWrapper()
        this.on('mount', function(){
            this.initImage()
        })

        this.on('update', function(){
            this.initClassContainer()
            this.initClassWrapper()
            this.initImage()
        })

        $(window).on('resize', function(){
            this.initImage()
            this.update()
        }.bind(this))
    </script>
</vd-block-image>
<vd-block-image_caption>
    <div class="vd-image-caption-container vd-image-caption-text-{getState().setting.global.position_text} {getState().setting.global.size=='responsive'?'responsive':''}">
        <div class="vd-image-caption-text {_.indexOf(['top', 'left'], getState().setting.global.position_text)}" if={_.indexOf(['top', 'left'], getState().setting.global.position_text) != -1}>
            <h4 if={getState().setting.global.title}>{getState().setting.global.title}</h4>
            <p class="vd-image-caption-main-text"><raw html={getState().setting.user.text}/></p>
        </div>

        <div class="vd-image-caption-wrapper vd-image-caption-size-{getState().setting.global.size}">
            <div class="vd-image-caption vd-animate-{getState().setting.global.animate}">
                <a if={getState().setting.global.onclick == 'popup'} class="image-popup" href="{getState().setting.user.popup}">
                    <img src="{getState().setting.user.thumb}" width="{getState().setting.user.width}" height="{getState().setting.user.height}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
                </a>
                <a if={getState().setting.global.onclick == 'link'} class="image-popup" href="{getState().setting.global.link}" target={getState().setting.global.link_target == 'new' ? '_blank' : '_self'}>
                    <img src="{getState().setting.user.thumb}" width="{getState().setting.user.width}" height="{getState().setting.user.height}" alt="{setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
                </a>
                <img if={getState().setting.global.onclick == ''} src="{getState().setting.user.thumb}" width="{getState().setting.user.width}" height="{getState().setting.user.height}" alt="{getState().setting.global.image_alt}" title="{getState().setting.global.image_title}"/>
            </div>
        </div>
        <div class="vd-image-caption-text vd-image-caption-text-{getState().setting.global.position_text}" if={_.indexOf(['bottom', 'right'], getState().setting.global.position_text) != -1}>
            <h4 if={getState().setting.global.title}>{getState().setting.global.title}</h4>
            <p class="vd-image-caption-main-text"><raw html={getState().setting.user.text}/></p>
        </div>
</div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initPopup()
        })
        this.on('updated', function(){
            this.initPopup()
        })
        this.initPopup = function(){
            var setting = this.getState().setting
            if(setting.global.padding_text) {
                $('.vd-image-caption-text', this.root).css({'padding':setting.global.padding_text})
            } else {
                $('.vd-image-caption-text', this.root).css({'padding':''})
            }
            
            if(!setting.global.display_border) {
                $('.vd-image-caption-container', this.root).css({'border-width': 0})
            } else {
                $('.vd-image-caption-container', this.root).css({'border-width': ''})
            }
            if(setting.global.onclick == 'popup') {
                $('.vd-image-caption', this.root).magnificPopup({
                        type:'image',
                        delegate: 'a',
                        gallery: {
                            enabled:true
                        }
                });
            }
        }
    </script>
</vd-block-image_caption>
<vd-block-images_carousel>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>

    <div class="carousel">
        <virtual each={image, key in getState().setting.user.images}>
            <a href="{image.popup}" if={getState().setting.global.onclick == 'popup'}><img src="{image.thumb}"/></a>
            <a class="image-popup" href="{getLink(key)}" target={getState().setting.global.link_target=='new' ? '_blank': ''} if={getState().setting.global.onclick == 'link'}><img src="{image.thumb}"/></a>
            <a if={getState().setting.global.onclick != 'link' && getState().setting.global.onclick != 'popup'}><img src="{image.thumb}"/></a>
        </virtual>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initCarousel();
        })
        this.on('update', function(){
            var owl = $('.carousel', this.root).data('owlCarousel')
            if(!_.isUndefined(owl)) {
                owl.destroy()
            }
        })
        this.on('updated', function(){
            this.initCarousel();
        })
        this.getLink = function(number) {
            var setting = this.getState().setting
            var index = _.indexOf(_.map(_.keys(setting.user.images), function(value){return Number(value)}), Number(number))
            var links = setting.global.link.split(/\n/)
            if(!_.isUndefined(links[index])){
                return links[index]
            } else {
                return ''
            }
        }
        this.initCarousel = function(){
            var setting = this.getState().setting
            var options = {
                items: setting.global.slides_per_view,
                autoWidth: true,
                center:true,
                responsive:true,
                responsiveRefreshRate:200,
                navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>']
            };
            if(setting.global.slides_per_view == 1){
                options['singleItem'] = true;
            }
            if(setting.global.auto_play){
                options['autoPlay'] = setting.global.speed;
            }
            if(setting.global.animate){
                options['transitionStyle'] = setting.global.animate;
            }

            options['stopOnHover'] = setting.global.stopOnHover
            options['lazyLoad'] = setting.global.stopOnHover
            options['pagination'] = !setting.global.hide_pagination_control
            options['navigation'] = !setting.global.hide_next_prev_button
            options['loop'] = !setting.global.hide_next_prev_button
            if(setting.global.onclick == 'popup') {
                $('.carousel', this.root).magnificPopup({
                    type:'image',
                    delegate: 'a',
                    gallery: {
                        enabled:true
                    }
                });
            }

            $('.carousel', this.root).owlCarousel(options);


        }
    </script>
</vd-block-images_carousel>
<vd-block-line_chart>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <canvas width="649" height="324"></canvas>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            animates: {
                easeoutbounce: 'easeOutBounce',
                easeoutelastic: 'easeOutElastic',
                easeoutback: 'easeOutBack',
                easeinoutcubic: 'easeInOutCubic',
                easeinoutquint: 'easeInOutQuint',
                easeinoutquart: 'easeInOutQuart',
                easeinquad: 'easeInQuad',
                easeoutsine: 'easeOutSine',
            }
        })
        this.on('updated', function(){
            this.initChart()
        })
        this.on('mount', function(){
            this.initChart()
        })
        this.hex2rgb = function(hex) {
            r = hex.match(/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i);
            if (r) {
                    return r.slice(1,4).map(function(x) { return parseInt(x, 16); });
            }
            r = hex.match(/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i);
            if (r) {
                    return r.slice(1,4).map(function(x) { return 0x11 * parseInt(x, 16); });
            }
            return null;
        }
        this.initChart = function(){
            var setting = this.getState().setting
            var animates = this.getState().animates

            var ctx = $('canvas', this.root).get(0).getContext("2d");
            var chartData =  {labels: setting.global.x_values.split(';'), datasets: []}
            for (var key in setting.global.values) {
                if(setting.global.mode == 'line') {
                    var opacityColor = this.hex2rgb(setting.global.values[key].color)
                    opacityColor = 'rgba('+opacityColor.join(',')+', 0.1)'
                    chartData.datasets.push({
                        label: setting.global.values[key].label,
                        data: setting.global.values[key].value.split(','),
                        borderColor: setting.global.values[key].color,
                        backgroundColor: opacityColor
                    })
                } else {
                    chartData.datasets.push({
                        label: setting.global.values[key].label,
                        data: setting.global.values[key].value.split(','),
                        backgroundColor: setting.global.values[key].color
                    })
                }
            }
            var chart = new Chart(ctx, {
                type: setting.global.mode,
                data: chartData,
                options: {
                    animation:{
                        duration:3000,
                        easing: animates[setting.global.animate]
                    },
                    hover:{
                        intersect:false
                    },
                    legend: {
                        display: setting.global.display_legend,
                        labels: {
                            display: setting.global.display_legend
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    </script>
</vd-block-line_chart>
<vd-block-mailchimp>
    <style scoped>
        :scope  .vd-mailchimp .form-inline{
            text-align: center;
        }
        :scope  .vd-mailchimp .form-inline .form-group{
            margin-left: 5px;
            margin-right: 5px; 
            margin-bottom: 10px;

        }
        :scope  .vd-mailchimp .form-inline .control-label{
            display: none;
        }
        :scope  .vd-mailchimp .text-danger {
            margin-bottom: 15px;
        }
        :scope  .vd-mailchimp .vd-mailchimp-submit{
            text-decoration: none;
            cursor:pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media only screen and (max-device-width:768px){

            :scope  .vd-mailchimp .form-group input{
                text-align:center;
                margin-left: auto;
                margin-right: auto;
            }

            :scope  .vd-mailchimp .form-group a{
                text-align:center;
                margin-left: auto;
                margin-right: auto;
            }
        }
        :scope .vd-mailchimp .vd-mailchimp-submit:hover{
            text-decoration: none;
        }
        :scope .vd-mailchimp {
            color: attr(button_color_text);
        }
    </style>
    <div class="vd-mailchimp" id="{opts.block.id}">
        <div class="text-danger" show={!_.isNull(getState().error)}>{getState().error}</div>
        <div class={getState().setting.global.inline?'form-inline': null} hide={getState().success}>
            <div class="form-group" hide={!getState().setting.display_firstname}>
                <label class="control-label">{store.getLocal('blocks.mailchimp.entry_firstname')}</label>
                <input class="form-control" type="text" name="firstname" value="" placeholder="{store.getLocal('blocks.mailchimp.entry_firstname')}" />
            </div>
            <div class="form-group"  hide={!getState().setting.display_lastname}>
                <label class="control-label">{store.getLocal('blocks.mailchimp.entry_lastname')}</label>
                <input class="form-control" type="text" name="lastname" value="" placeholder="{store.getLocal('blocks.mailchimp.entry_lastname')}" />
            </div>
            <div class="form-group required {!_.isNull(getState().error)? 'has-error': ''}">
                <label class="control-label">{store.getLocal('blocks.mailchimp.entry_email')}</label>
                <input class="form-control" type="text" name="email" value="" placeholder="{store.getLocal('blocks.mailchimp.entry_email')}" />
            </div>
            <div class="form-group ">
                <a onclick={subscribe} class="vd-mailchimp-submit {getState().setting.global.button_style != 'custom' ? 'btn btn-'+getState().setting.button_style : ''}">{getState().setting.global.button_title}</a>
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
            var form = $('.vd-mailchimp', this.root);
            data = {
                api: setting.global.api,
                list_id: setting.global.list_id,
                email: form.find('input[name=email]').val(),
                firstname: form.find('input[name=firstname]').val(),
                lastname: form.find('input[name=lastname]').val()
            }

            $.ajax({
                url: 'index.php?route=extension/d_visual_designer_module/mailchimp/subscribe',
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
                $('.vd-mailchimp-submit', this.root).attr({'button_color_text': setting.global.button_color_text})

                $('.vd-mailchimp-submit', this.root).css({
                    'border-width': setting.global.button_border_width,
                    'border-style': 'solid',
                    'font-size': setting.global.button_font_size.replace('px', '')+'px',
                    'border-radius': setting.global.button_border_radius+'px'
                })
                if(setting.global.button_width) {
                    $('.vd-mailchimp-submit', this.root).css({'width': setting.global.button_width})
                } else {
                    $('.vd-mailchimp-submit', this.root).css({'width': '50px'})
                }
                if(setting.global.button_height) {
                    $('.vd-mailchimp-submit', this.root).css({'height': setting.global.button_height})
                } else {
                    $('.vd-mailchimp-submit', this.root).css({'height': '20px'})
                }
            }

            $('input[type=text]', this.root).css({
                'border-width': setting.global.input_border_width,
                'border-style': 'solid',
                'font-size': setting.global.input_font_size,
                'border-radius': setting.global.input_border_radius,
                'background-color': setting.global.input_background_color,
                'color': setting.global.input_color_text
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
                '.vd-mailchimp-submit': {
                    'background-color': setting.global.button_background_color,
                    'color': setting.global.button_color_text,
                    'border-color':setting.global.button_border_color
                },
                '.vd-mailchimp-submit:hover': {
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
</vd-block-mailchimp>
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
<vd-block-message_box>
    <style>
        .vd-message{
            width: 100%;
            min-height: 45px;
            border: 1px solid transparent;
            display: block;
            overflow: hidden;
            padding: 1em 1em 1em 4em;
            position: relative;
            font-size: 1em;
            box-sizing: border-box;
        }
        .vd-message > .icon{
            bottom: 0;
            font-size: 1em;
            font-style: normal;
            font-weight: 400;
            left: 0;
            position: absolute;
            top: 0;
            width: 4em;
            color:white;
        }
        .vd-message > .icon > span{
            font-style: normal;
            left: 30%;
            position: absolute;
            top: 30%;
            font-size: 1.7em;
            line-height: 1;
        }
        .vd-message > .text{
            float:left;
            color:white;
        }
        .vd-message-share-round{
            border-radius: 4em;
        }
        .vd-message-share-rounded{
            border-radius: 4px;
        }
    </style>
    <div class="vd-message vd-message-style-{getState().setting.global.style} vd-message-share-{getState().setting.global.share}" style="background:{getState().setting.global.color};">
        <div class="icon">
            <span class="{getState().setting.global.icon}" style="color:{getState().setting.global.border_color}"></span>
        </div>
        <div class="text" style="color:{getState().setting.global.border_color}">
            <raw html={getState().setting.user.text}/>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-message_box>
<vd-block-module>
    <raw html={getState().setting.user.text}/>
    <link href="{link.href}" rel="{link.rel}" each={link in getState().setting.user.styles}/>
    <div class="scripts"></div>
    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            setTimeout(function(){
                $('.scripts', this.root).empty();
                for(var key in setting.user.scripts) {
                    var script = document.createElement('script')
                    script.src=setting.user.scripts[key]
                    script.type="text/javascript"
                    $('.scripts', this.root).append(script)
                }
            }.bind(this), 100)
            var setting = this.getState().setting

        })
        this.on('updated', function(){
            var setting = this.getState().setting
            $('.scripts', this.root).empty();
            for(var key in setting.user.scripts) {
                var script = document.createElement('script')
                script.src=setting.user.scripts[key]
                script.type="text/javascript"
                $('.scripts', this.root).append(script)
            }
        })
    </script>
</vd-block-module>
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
<vd-block-pinterest_like>
    <div class="vd-pinterest-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
         this.on('mount', function(){
            this.initPinterestLike();
        })
        this.on('update', function(){
            this.initPinterestLike();
        })
        this.initPinterestLike = function(){
            var setting = this.getState().setting
            $('.vd-pinterest-like', this.root).empty()
            $('.vd-pinterest-like', this.root).sharrre({
                share: {
                    pinterest: true,
                },
                buttons: {
                    pinterest: {
                        layout: setting.global.type_button
                    },
                },
                enableHover: false,
                enableCounter: false,
                enableTracking: true
            });
        }
    </script>
</vd-block-pinterest_like>
<vd-block-popup>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-popup>
<vd-block-pricing_table>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-pricing_table>
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
<vd-block-progress_bar>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <div class="progress" each={value, key in getState().setting.global.values}>
        <div class="progress-bar progress-bar-danger {getState().setting.global.stripes?'progress-bar-striped':''} {getState().setting.global.animate?'active':''}" role="progressbar" aria-valuenow="{value.value}" aria-valuemin="0" aria-valuemax="100" style="width: {value.value}%; background-color:{value.color}">
            {value.label} {value.value}{getState().setting.global.units} 
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-progress_bar>
<vd-block-round_chart>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <canvas></canvas>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            animates: {
                easeoutbounce: 'easeOutBounce',
                easeoutelastic: 'easeOutElastic',
                easeoutback: 'easeOutBack',
                easeinoutcubic: 'easeInOutCubic',
                easeinoutquint: 'easeInOutQuint',
                easeinoutquart: 'easeInOutQuart',
                easeinquad: 'easeInQuad',
                easeoutsine: 'easeOutSine',
            }
        })
        this.on('updated', function(){
            this.initChart()
        })
        this.on('mount', function(){
            this.initChart()
        })
        this.animates = {
            easeoutbounce: 'easeOutBounce',
            easeoutelastic: 'easeOutElastic',
            easeoutback: 'easeOutBack',
            easeinoutcubic: 'easeInOutCubic',
            easeinoutquint: 'easeInOutQuint',
            easeinoutquart: 'easeInOutQuart',
            easeinquad: 'easeInQuad',
            easeoutsine: 'easeOutSine',
        }
        this.initChart = function(){
            var setting = this.getState().setting
            var animates = this.getState().animates
            var ctx = $('canvas', this.root).get(0).getContext("2d");
            var chartData =  {labels: [], datasets: [{data:[], backgroundColor:[], label: setting.global.title}]}

            for (var key in setting.global.values) {
                chartData.datasets[0].data.push(setting.global.values[key].value)
                chartData.labels.push(setting.global.values[key].label)
                chartData.datasets[0].backgroundColor.push(setting.global.values[key].color)
            }

            var chart = new Chart(ctx, {
                type: setting.global.mode,
                data: chartData,
                options: {
                    animation:{
                        duration:3000,
                        easing: animates[setting.global.animate]
                    },
                    hover:{
                        intersect:false
                    },
                    elements:{
                        arc:{
                            borderWidth: setting.global.stroke_width
                        }
                    },
                    legend: {
                        display: setting.global.display_legend,
                        labels: {
                            display: setting.global.display_legend
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    </script>
</vd-block-round_chart>
<vd-block-row>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>

    <div class="video-background" if={getLink() && getState().setting.global.background_video}>
        <iframe src="{getLink()}" frameborder="0" allowfullscreen="1" width="100%" height="100%" volume="0" onload={loadIframe}></iframe>
    </div>
    <script>
        this.mixin(new vd_block(this, false))
        
        this.on('updated', function(){
            this.reCalculate()
        })
        this.on('mount', function(){
            this.reCalculate()
        })
        this.loadIframe = function(e) {
            this.reCalculate()
        }.bind(this)

        this.getLink = function(){
            var link = ''
            var setting = this.getState().setting
            if(setting.global.link.indexOf('youtube') != -1){
                var matches = setting.global.link.match(/(v=)([a-zA-Z0-9]+)/)
                if(matches != null){
                    var youtube_id = matches[2]
                    link = setting.global.link.replace('watch?v=', 'embed/') + "?playlist="+youtube_id+"&autoplay=1&controls=0&showinfo=0&disablekb=1&loop=1&rel=0&modestbranding"
                }
            } else if (setting.global.link.indexOf('vimeo') != -1){
                link = setting.global.link.replace('vimeo.com', 'player.vimeo.com/video') + '?autoplay=1&background=1&loop=1'
            }
            return link
        }.bind(this)
        
        this.reCalculate = function(){
            var content = $(this.root).closest('.block-container')
            content.css('position','');
            content.css('z-index','');
            content.css('left','');
            content.css('width','');

            if(this.getState('setting').global.design_padding_left == ''){
                content.css('padding-left','');
            }
            if(this.getState('setting').global.design_padding_right == ''){
                content.css('padding-right','');
            }
            var width_content = content.outerWidth();
            if(this.getState('setting').global.row_stretch !== '') {
                var left = content.offset().left - $('body').offset().left;
                var width_window = $('body').width();
                var right = width_window - left - content.width();
                content.css('position','relative');
                content.css('z-index','2');
                var direction = $('body').css('direction');
                if(direction == 'rtl'){
                    content.css('right','-'+right+'px');
                } else {
                    content.css('left','-'+left+'px');
                }
                content.css('width',width_window+'px');
                width_content = width_window;
                if(this.getState('setting').global.row_stretch === 'stretch_row'){
                    content.css('padding-left',left+'px');
                    content.css('padding-right',right+'px');
                }
                if(this.getState('setting').global.row_stretch === 'stretch_row_content_left'){
                    content.css('padding-right',right+'px');
                }
                if(this.getState('setting').global.row_stretch === 'stretch_row_content_right'){
                    content.css('padding-left',left+'px');
                }
            }
            if(this.getLink() && this.getState('setting').global.background_video){
                var video = $('.video-background', this.root);
                var height_content = content.outerHeight();
                var width = height_content/9*16;
                var height = height_content;

                if(width < width_content){
                    width = width_content;
                    height = width/16*9;
                    var margintop = (height-height_content)/2;
                }
                else{
                    var margintop = 0;
                }
                var marginleft =(width - width_content)/2;
                video.find('iframe').css('height',height+'px');
                video.find('iframe').css('width',width+'px');
                video.find('iframe').css('max-width','1000%');
                video.find('iframe').css('margin-left','-'+marginleft+'px');
                video.find('iframe').css('margin-top','-'+margintop+'px');
            }
        }.bind(this)
        $(window).on('resize', function(){
            this.reCalculate()
        }.bind(this))
    </script>
</vd-block-row>
<vd-block-row_inner>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>

    <div class="video-background" if={getLink() && setting.global.background_video}>
        <iframe src="{getLink()}" frameborder="0" allowfullscreen="1" width="100%" height="100%" volume="0" onload={loadIframe}></iframe>
    </div>
    <script>
        this.mixin(new vd_block(this, false))
        this.on('updated', function(){
            this.reCalculate()
        })
        this.on('mount', function(){
            this.reCalculate()
        })
        loadIframe(e) {
            this.reCalculate()
        }.bind(this)

        this.getLink = function(){
            var link = ''
            if(this.getState('setting').global.link.indexOf('youtube') != -1){
                var matches = this.getState('setting').global.link.match(/(v=)([a-zA-Z0-9]+)/)
                if(matches != null){
                    var youtube_id = matches[2]
                    link = this.getState('setting').global.link.replace('watch?v=', 'embed/') + "?playlist="+youtube_id+"&autoplay=1&controls=0&showinfo=0&disablekb=1&loop=1&rel=0&modestbranding"
                }
            } else if (this.getState('setting').global.link.indexOf('vimeo') != -1){
                link = this.getState('setting').global.link.replace('vimeo.com', 'player.vimeo.com/video') + '?autoplay=1&background=1&loop=1'
            }
            return link
        }.bind(this)
        
        this.reCalculate = function(){
            var content = $(this.root).closest('.block-container')
            content.css('position','');
            content.css('z-index','');
            content.css('left','');
            content.css('width','');
            if(this.getState('setting').global.design_padding_left == ''){
                content.css('padding-left','');
            }
            if(this.getState('setting').global.design_padding_right == ''){
                content.css('padding-right','');
            }
            var width_content = content.outerWidth();
            if(this.getLink() && this.getState('setting').global.background_video){
                var video = $('.video-background', this.root);
                var height_content = content.outerHeight();
                var width = height_content/9*16;
                var height = height_content;

                if(width < width_content){
                    width = width_content;
                    height = width/16*9;
                    var margintop = (height-height_content)/2;
                }
                else{
                    var margintop = 0;
                }
                var marginleft =(width - width_content)/2;
                video.find('iframe').css('height',height+'px');
                video.find('iframe').css('width',width+'px');
                video.find('iframe').css('max-width','1000%');
                video.find('iframe').css('margin-left','-'+marginleft+'px');
                video.find('iframe').css('margin-top','-'+margintop+'px');
            }
        }.bind(this)
        $(window).on('resize', function(){
            this.reCalculate()
        }.bind(this))
    </script>
</vd-block-row_inner>
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
<vd-block-section>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-section>
<vd-block-section_accordion>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-section_accordion>
<vd-block-section_wrapper>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>

    <div class="video-background" if={getLink() && getState().setting.global.background_video}>
        <iframe src="{getLink()}" frameborder="0" allowfullscreen="1" width="100%" height="100%" volume="0" onload={loadIframe}></iframe>
    </div>
    <script>
        this.mixin(new vd_block(this, false))
        
        this.on('updated', function(){
            this.reCalculate()
        })
        this.on('mount', function(){
            this.reCalculate()
        })
        this.loadIframe = function(e) {
            this.reCalculate()
        }.bind(this)

        this.getLink = function(){
            var link = ''
            var setting = this.getState().setting
            if(setting.global.link.indexOf('youtube') != -1){
                var matches = setting.global.link.match(/(v=)([a-zA-Z0-9]+)/)
                if(matches != null){
                    var youtube_id = matches[2]
                    link = setting.global.link.replace('watch?v=', 'embed/') + "?playlist="+youtube_id+"&autoplay=1&controls=0&showinfo=0&disablekb=1&loop=1&rel=0&modestbranding"
                }
            } else if (setting.global.link.indexOf('vimeo') != -1){
                link = setting.global.link.replace('vimeo.com', 'player.vimeo.com/video') + '?autoplay=1&background=1&loop=1'
            }
            return link
        }.bind(this)
        
        this.reCalculate = function(){
            var content = $(this.root).closest('.block-container')
            content.css('position','');
            content.css('z-index','');
            content.css('left','');
            content.css('width','');

            if(this.getState('setting').global.design_padding_left == ''){
                content.css('padding-left','');
            }
            if(this.getState('setting').global.design_padding_right == ''){
                content.css('padding-right','');
            }
            var width_content = content.outerWidth();
            if(this.getState('setting').global.row_stretch !== '') {
                var left = content.offset().left - $('body').offset().left;
                var width_window = $('body').width();
                var right = width_window - left - content.width();
                content.css('position','relative');
                content.css('z-index','2');
                var direction = $('body').css('direction');
                if(direction == 'rtl'){
                    content.css('right','-'+right+'px');
                } else {
                    content.css('left','-'+left+'px');
                }
                content.css('width',width_window+'px');
                width_content = width_window;
                if(this.getState('setting').global.row_stretch === 'stretch_row'){
                    content.css('padding-left',left+'px');
                    content.css('padding-right',right+'px');
                }
                if(this.getState('setting').global.row_stretch === 'stretch_row_content_left'){
                    content.css('padding-right',right+'px');
                }
                if(this.getState('setting').global.row_stretch === 'stretch_row_content_right'){
                    content.css('padding-left',left+'px');
                }
            }
            if(this.getLink() && this.getState('setting').global.background_video){
                var video = $('.video-background', this.root);
                var height_content = content.outerHeight();
                var width = height_content/9*16;
                var height = height_content;

                if(width < width_content){
                    width = width_content;
                    height = width/16*9;
                    var margintop = (height-height_content)/2;
                }
                else{
                    var margintop = 0;
                }
                var marginleft =(width - width_content)/2;
                video.find('iframe').css('height',height+'px');
                video.find('iframe').css('width',width+'px');
                video.find('iframe').css('max-width','1000%');
                video.find('iframe').css('margin-left','-'+marginleft+'px');
                video.find('iframe').css('margin-top','-'+margintop+'px');
            }
        }.bind(this)
        $(window).on('resize', function(){
            this.reCalculate()
        }.bind(this))
    </script>
</vd-block-section_wrapper>
<vd-block-separater>
    <style>
        .vd-separater-left{
            margin-right:auto;
        }
        .vd-separater-center{
            margin-left:auto;
            margin-right:auto;
        }
        .vd-separater-right{
            margin-left:auto;
        }
    </style>
    <div style="width:100%;">
        <div class="vd-separater-{getState().setting.global.align}" style="width:{getState().setting.global.width};">
            <hr style="border-top: {getState().setting.global.border_width} {getState().setting.global.style} {getState().setting.global.color};"/>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-separater>
<vd-block-social_icons>
    <!--  <style>
        .vd-social-icon-container {
            display: flex;
            flex-flow: row;
        }
        .vd-social-icon-align-left {
            justify-content: flex-start;
        }
        .vd-social-icon-align-center {
            justify-content: center;
        }
        .vd-social-icon-align-right {
            justify-content: flex-end;
        }
        .vd-social-icon-container > .vd-social-icon{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vd-social-icon-container > .vd-social-icon:hover{
            text-decoration: none;
        }
    </style>  -->
    <div class="vd-social-icon-container {getState().className}">
        <virtual each="{icon, name in getState().icons}">
            <a if={icon.link} href="{icon.link}" target="_blank" class="vd-social-icon {name}"><i class="{icon.icon}" aria-hidden="true"></i></a>
        </virtual>
    </div>
    <script>
        this.mixin(new vd_block(this))
        this.initState({
            className: ''
        })
        _.mixin({
            'sortKeysBy': function (obj, comparator) {
                var keys = _.sortBy(_.keys(obj), function (key) {
                    return comparator ? comparator(obj[key], key) : key;
                });
            
                return _.object(keys, _.map(keys, function (key) {
                    return obj[key];
                }));
            }
        });
        
        this.initState({
            icons: _.sortKeysBy(this.getState().setting.global.icons, function(icon){ return icon.sort_order})
        })

        this.initClass = function(){
            var className = []
            var setting = this.getState().setting
            if(setting.global.align) {
                className.push('vd-si-align-'+setting.global.align)
            }
            if(setting.global.icon_type) {
                className.push('vd-si-type-'+setting.global.icon_type)
            }
            if(setting.global.align_phone) {
                className.push('vd-si-phone-align-'+setting.global.align_phone)
            }
            if(setting.global.align_tablet) {
                className.push('vd-si-tablet-align-'+setting.global.align_tablet)
            }
            this.setState({
                className: className.join(' ')
            })
        }

        this.initStyle = function(){
            var setting = this.getState().setting
            var stylesMedia = {
                'phone': {
                    '.vd-social-icon': {
                        'margin-right': setting.global.margin_beetwen_phone
                    }
                },
                'tablet': {
                    '.vd-social-icon': {
                        'margin-right': setting.global.margin_beetwen_tablet
                    }
                },
                'desktop': {
                    '.vd-social-icon': {
                        'margin-right': setting.global.margin_beetwen
                    }
                }
            }
            var styles = {}
            for(var name in setting.global.icons) {
                var icon = setting.global.icons[name]
                if(setting.global.icon_type == 'circle') {
                    styles['.vd-social-icon.'+name] = {
                        'background-color': icon.background,
                        'width': 'calc('+icon.size+' + '+icon.size+')', 
                        'height': 'calc('+icon.size+' + '+icon.size+')'
                    }
                }
                styles['.vd-social-icon.'+name+' > i'] = {'color': icon.color, 'font-size': icon.size}
            }
            this.store.dispatch('block/style/update', {designer_id: this.getState().top.opts.id, block_id: this.opts.block.id, styles: styles})
            this.store.dispatch('block/style/media/update', {designer_id: this.getState().top.opts.id, block_id: this.opts.block.id, styles: stylesMedia})
        }

        this.initClass()

        this.on('update', function(){
            this.setState({
                 icons: _.sortKeysBy(this.getState().setting.global.icons, function(icon){ return icon.sort_order})
            })
            this.initClass()
        })

        this.on('updated', function(){
            this.initStyle();
        })


        this.on('mount', function(){
            this.initStyle();
        })


    </script>
</vd-block-social_icons>
<vd-block-star_rating>
    <style type="text/css" media="screen">
        .vd-rating{
            text-align: center;
            width: 100%;
        }
        .vd-rating > .vd-rating-stars {
            overflow: auto;
            display: flex;
            justify-content: center;
        }
        .vd-rating > .vd-rating-stars > i{
            float:left;
        }
        .vd-rating > .title{
            display: flex;
            justify-content: center;
        }
        .vd-rating .fas.fa-star{
            color: #FC0;
        }
        .vd-rating .fa-1x{
            font-size: 16px;
        }
        .vd-rating .vd-rating-stars-stack{
            position: relative;
            margin: 2px;
        }
        .vd-rating .far.fa-star {
            color: #999;
        }
        .vd-rating .fas.fa-star + .far.fa-star {
            position: absolute;
            color: #E69500;
            left: 0;
            top: 0;
            width: 100%;
            text-align: center;
        }
    </style>
    <div class="vd-rating">
        <div class="vd-rating-stars">
            <span class="vd-rating-stars-stack" each={value in _.range(1, (Number(getState().setting.global.rating)+1))}>
                <i class="fas fa-star fa-{getState().setting.global.star_size}x" aria-hidden="true"></i>
                <i class="far fa-star fa-{getState().setting.global.star_size}x" aria-hidden="true"></i>
            </span>
            <span class="vd-rating-stars-stack" each={value in _.range(Number(getState().setting.global.rating), 5)} if={getState().setting.global.rating < 5}>
                <i class="far fa-star fa-{getState().setting.global.star_size}x" aria-hidden="true"></i>
            </span>
        </div>
        <p class="title">{getState().setting.global.title}</p>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-star_rating>
<vd-block-tabs>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-tabs>
<vd-block-testimonial>
    <div class="vd-testimonial">
        <div class="vd-testimonial-image">
            <img src="{getState().setting.user.thumb}"/>
        </div>
        <div class="vd-testimonial-name h3">{getState().setting.global.name}</div>
        <div class="vd-testimonial-comment"><raw html={getState().setting.user.comment}/></div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-testimonial>
<vd-block-text>
    <raw html={getState().setting.user.text}/>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-text>
<vd-block-text_separater>
    <div class="vd-text-separater-container vd-text-separater-align-{getState().setting.global.align}">
        <div class="vd-text-separater-wrapper">
            <div class="vd-text-separater left" if={_.indexOf(['right', 'center'], getState().setting.global.title_align) != -1}>
                <hr/>
            </div>
            <h2 class="vd-separater-title">{getState().setting.global.title}</h2>
            <div class="vd-text-separater right" if={_.indexOf(['left', 'center'], getState().setting.global.title_align) != -1}>
                <hr/>
            </div>
        </div>
    </div>

    <script>
        this.mixin(new vd_block(this))
        this.on('mount', function(){
            this.initSeparator()
        })
        this.on('updated', function(){
            this.initSeparator();
        })
        this.initSeparator = function(){
            var setting = this.getState().setting
            if(setting.global.font_family != 'default') {
                WebFont.load({
                    google: {
                        families: [setting.global.font_family, setting.global.font_family+' i7']
                    }
                });
            }
            $('.vd-separater-title', this.root).css({'color': setting.global.color_title})
            if(setting.global.font_family) {
                $('.vd-separater-title', this.root).css({'font-family': setting.global.font_family}) 
            } else {
                $('.vd-separater-title', this.root).css({'font-family': ''}) 
            }
            if(setting.global.font_size) {
                $('.vd-separater-title', this.root).css({'font-size': setting.global.font_size})
            } else {
                $('.vd-separater-title', this.root).css({'font-size': ''})
            }
            if(setting.global.bold) {
                 $('.vd-separater-title', this.root).css({'font-weight': '700'});
            } else {
                 $('.vd-separater-title', this.root).css({'font-weight': ''});
            }
            if(setting.global.italic) {
                 $('.vd-separater-title', this.root).css({'font-style': 'italic'});
            } else {
                 $('.vd-separater-title', this.root).css({'font-style': ''});
            }
            if(setting.global.underline) {
                $('.vd-separater-title', this.root).css({'text-decoration': 'underline'});
            } else {
                $('.vd-separater-title', this.root).css({'text-decoration': ''});
            }
            $('.vd-text-separater-wrapper').css({'flex': '0 0 '+setting.global.width})
            $('hr', this.root).css({'border-top': setting.global.border_width+' '+setting.global.border_style+' '+setting.global.border_color})
        }
    </script>
</vd-block-text_separater>
<vd-block-tour>
    <virtual data-is="wrapper-blocks" block={opts.block}></virtual>
    <script>
        this.mixin(new vd_block(this, false))
    </script>
</vd-block-tour>
<vd-block-trust>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <div>
        <a each={image in getState().setting.user.images} class="image-popup" target="_blank" href="{image.link}" style="text-decoration: none;">
            <img src="{image.thumb}"/>
        </a>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-trust>
<vd-block-twitter_like>
    <div class="vd-twitter-like" data-title="sharrre"></div>
    <script>
        this.mixin(new vd_block(this))
         this.on('mount', function(){
            this.initTwitterLike();
        })
        this.on('update', function(){
            this.initTwitterLike();
        })
        this.initTwitterLike = function(){
            $('.vd-twitter-like', this.root).empty()
            $('.vd-twitter-like', this.root).sharrre({
                share: {
                    twitter: true,
                },
                buttons: {
                    twitter: {count: 'horizontal'},
                },
                enableHover: false,
                enableCounter: false,
                enableTracking: true
            });
        }
    </script>
</vd-block-twitter_like>
<vd-block-twitter_widget>
    <div style="text-align: center;" class="widget"></div>
    <script>
        this.mixin(new vd_block(this))
        this.initTwitter = function(){
            var setting = this.getState().setting
            var element = document.createElement('a')
            if(setting.global.height) {
                element.setAttribute('data-height', setting.global.height);
            }
            if(setting.global.width) {
                element.setAttribute('data-width', setting.global.width);
            }
            element.setAttribute('data-theme', setting.global.theme);
            element.setAttribute('data-link-color', setting.global.color_link);
            element.setAttribute('class', 'twitter-timeline');
            element.setAttribute('href', setting.global.href);

            $('.widget', this.root).html(element);
            twttr.widgets.load();
        }
        this.on('mount', function(){
            this.initTwitter()
        })
        this.on('update', function(){
            this.initTwitter()
        })
    </script>
</vd-block-twitter_widget>
<vd-block-video>
    <div style="width:{getState().setting.global.width}">
        <div class="wrapper-video vd-video-aspect-ration-{getState().setting.global.ratio}">
            <iframe src="{getLink()}" frameborder="0" allowfullscreen width="638" height="358" class="video-player"></iframe>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))

        this.getLink = function(){
            var link = ''
            var setting = this.getState().setting
            if(setting.global.link.indexOf('youtube') != -1){
                var matches = setting.global.link.match(/(v=)([a-zA-Z0-9]+)/)
                if(matches != null){
                    var youtube_id = matches[2]
                    link = setting.global.link.replace('watch?v=', 'embed/') + "?playlist="+youtube_id
                }
            } else if (setting.global.link.indexOf('vimeo') != -1){
                link = setting.global.link.replace('vimeo.com', 'player.vimeo.com/video')
            }
             link = link.replace('https:', '').replace('http:', '')
            return window.location.protocol + link
        }.bind(this)
    </script>
</vd-block-video>
