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