<vd-setting-block-images_carousel>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}>
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <td class="text-left">{store.getLocal('blocks.images_carousel.entry_additional_image')}</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr id="image-row" each={image, key in setting.edit.images}>
                <td class="text-left">

                    <a href="" id="thumb-vd-image{key}" data-toggle="vd-image" class="img-thumbnail">
                        <img src="{image.thumb}" alt="" title="" data-placeholder="{store.getOptions('blocks.images_carousel.placeholder')}" />
                    </a>

                    <input type="hidden" name="images[{key}]" value="{image.url}" id="input-vd-image{key}" onchange={changeImage} />
                </td>
                <td class="text-left"><button type="button" onclick={removeImage} data-toggle="tooltip" title="{store.getLocal('blocks.images_carousel.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="text-left"><button type="button" onclick={addImage} data-toggle="tooltip" title="{store.getLocal('blocks.images_carousel.button_image_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
            </tr>
        </tfoot>
    </table>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.images_carousel.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="size" hide={setting.global.size != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.images_carousel.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" class="form-control pixels" value="{setting.global.width}" onChange={change}>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.images_carousel.entry_height')}</label>
            <div class="fg-setting">
                <input type="text" name="height" class="form-control pixels" value="{setting.global.height}" onChange={change}>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_speed')}</label>
        <div class="fg-setting">
            <input type="text" name="speed" class="form-control" value="{setting.global.speed}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_slides_per_view')}</label>
        <div class="fg-setting">
            <input type="text" name="slides_per_view" class="form-control" value="{setting.global.slides_per_view}" onChange={change}>
        </div>
    </div>
     <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_onclick')}</label>
        <div class="fg-setting">
            <select class="form-control" name="onclick" onChange={change}>
                <option value="{key}" selected={key == setting.global.onclick} each={value, key in store.getOptions('blocks.images_carousel.actions')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="link" hide={setting.global.onclick != 'link'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.images_carousel.entry_link')}</label>
            <div class="fg-setting">
                <textarea name="link" class="form-control" onchange={change}>{setting.global.link}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.images_carousel.entry_link_target')}</label>
            <div class="fg-setting">
                <select class="form-control" name="link_target" onChange={change}>
                    <option value="new" selected={'new' == setting.global.link_target}>{store.getLocal('blocks.images_carousel.text_new_window')}</option>
                    <option value="current" selected={'current' == setting.global.link_target}>{store.getLocal('blocks.images_carousel.text_current_window')}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_animate')}</label>
        <div class="fg-setting">
            <select class="form-control" name="animate" onChange={change}>
                <option value="{key}" selected={key == setting.global.animate} each={value, key in store.getOptions('blocks.images_carousel.animates')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_auto_play')}</label>
        <div class="fg-setting">
            <vd-switcher name="auto_play" value="{setting.global.auto_play}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_hide_pagination_control')}</label>
        <div class="fg-setting">
            <vd-switcher name="hide_pagination_control" value="{setting.global.hide_pagination_control}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_hide_next_prev_button')}</label>
        <div class="fg-setting">
            <vd-switcher name="hide_next_prev_button" value="{setting.global.hide_next_prev_button}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_stop_on_hover')}</label>
        <div class="fg-setting">
            <vd-switcher name="stopOnHover" value="{setting.global.stopOnHover}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.images_carousel.entry_lazy_load')}</label>
        <div class="fg-setting">
            <vd-switcher name="lazyLoad" value="{setting.global.lazyLoad}" evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })

        addImage(e) {
            var field_setting = {thumb: this.store.getOptions('blocks.images_carousel.placeholder'), url:''};
            var languages = this.store.getOptions('blocks.feedback.languages')
             if(_.isEmpty(this.setting.global.images)) {
                this.setting.global.images = {0: ''}
                this.setting.edit.images = {0: field_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.images), function(value){return Number(value)})) + 1
                this.setting.edit.images[new_index] = field_setting
                this.setting.global.images[new_index] = ''
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeImage(e) {
            this.setting.global.images = _.pick(this.setting.global.images, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })
            this.setting.edit.images = _.pick(this.setting.edit.images, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeImage(e) {
            this.setting.global.images[e.item.key] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }

        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
    </script>
</vd-setting-block-images_carousel>