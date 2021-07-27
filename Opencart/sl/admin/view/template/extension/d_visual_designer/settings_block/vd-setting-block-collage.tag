<vd-setting-block-collage>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.collage.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.collage.entry_scheme')}</label>
        <div class="fg-setting">
            <input type="text" name="scheme" class="form-control" value="{setting.global.scheme}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.collage.entry_images')}</label>
        <div class="fg-setting">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{store.getLocal('blocks.collage.entry_additional_image')}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                        <tr id="image-row{key}" each={image, key in setting.edit.images}>
                            <td class="text-left">

                                <a href="" id="thumb-vd-image{key}" data-toggle="vd-image" class="img-thumbnail">
                                    <img src="{image.thumb}" alt="" title="" data-placeholder="{store.getOptions('blocks.collage.placeholder')}" />
                                </a>

                                <input type="hidden" name="images[{key}]" value="{image.url}" id="input-vd-image{key}" onchange={changeImage} />
                            </td>
                            <td class="text-left"><button type="button" onclick={removeImage} data-toggle="tooltip" title="{store.getLocal('blocks.collage.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-left"><button type="button" onclick={addImage} data-toggle="tooltip" title="{store.getLocal('blocks.collage.button_image_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.collage.entry_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="border_width" min="0" value={setting.global.border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.collage.entry_onclick')}</label>
        <div class="fg-setting">
            <select class="form-control" name="onclick" onChange={change}>
                <option value="{key}" selected={key == setting.global.onclick} each={value, key in store.getOptions('blocks.collage.actions')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="link" hide={setting.global.onclick != 'link'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.collage.entry_link')}</label>
            <div class="fg-setting">
                <input type="text" name="link" class="form-control" value="{setting.global.link}" onchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.collage.entry_link_target')}</label>
            <div class="fg-setting">
                <select class="form-control" name="link_target" onChange={change}>
                    <option value="new" selected={'new' == setting.global.link_target}>{store.getLocal('blocks.collage.text_new_window')}</option>
                    <option value="current" selected={'current' == setting.global.link_target}>{store.getLocal('blocks.collage.text_current_window')}</option>
                </select>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addImage(e) {
            var field_setting = {thumb: this.store.getOptions('blocks.collage.placeholder'), url:''};
            var languages = this.store.getOptions('blocks.feedback.languages')
            if(_.isEmpty(this.setting.global.images)) {
                this.setting.global.images = {0: ''}
                this.setting.edit.images = {0: field_setting}
                this.setting.user.images = {0: field_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.images), function(value){return Number(value)})) + 1
                this.setting.user.images[new_index] = field_setting
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
            this.setting.user.images = _.pick(this.setting.user.images, function(value, key) {
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
</vd-setting-block-collage>