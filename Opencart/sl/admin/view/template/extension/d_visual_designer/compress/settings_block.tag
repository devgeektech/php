<vd-setting-block-accordion>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.accordion.entry_active_section')}</label>
        <div class="fg-setting">
            <input class="form-control" name="active_section" value="{setting.global.active_section}" onchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.accordion.entry_title')}</label>
        <div class="fg-setting">
            <input class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-accordion>
<vd-setting-block-button>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_text')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="text" value="{setting.global.text}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_action')}</label>
        <div class="fg-setting">
            <select class="form-control" name="action" onChange={change}>
                <option value="{key}" selected={key == setting.global.action} each={value, key in store.getOptions('blocks.button.actions')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="action-link" hide={setting.global.action != 'link'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_link')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_new_window')}</label>
            <div class="fg-setting">
                <vd-switcher name="new_window" value="{setting.global.new_window}" evchange={change}/>
            </div>
        </div>
    </div>
    <div id="action-buy" hide={setting.global.action != 'buy'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_product')}</label>
            <div class="fg-setting">
                <vd-autocomplete name="product_id" value={setting.global.product_id} url={store.getOptions('blocks.button.autocomplete')} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_quantity')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="quantity" value="{setting.global.quantity}" onChange={change}/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_text'} value={setting.global.color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="border_width" value={setting.global.border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'border_color'} value={setting.global.border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_radius')}</label>
        <div class="fg-setting">
            <vd-range name="border_radius" value={setting.global.border_radius} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_letter_spacing')}</label>
        <div class="fg-setting">
            <vd-range name="letter_spacing" value={setting.global.letter_spacing} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_font_family')}</label>
        <div class="fg-setting">
            <vd-font-picker name='font_family' value="{setting.global.font_family}" evchange={change}/>
            <vd-switcher name="underline" value="{setting.global.underline}" evchange={change} label={"<i class='fa fa-underline' aria-hidden='true'></i>"}/>
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change} label={"<i class='fa fa-bold' aria-hidden='true'></i>"}/>
            <vd-switcher name="italic" value="{setting.global.italic}" evchange={change} label={"<i class='fa fa-italic' aria-hidden='true'></i>"}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_display_icon')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_icon" value="{setting.global.display_icon}" evchange={change}/>
        </div>
    </div>
    <div id="icon-setting" hide={!setting.global.display_icon}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_library')}</label>
            <div class="fg-setting">
                <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon')}</label>
            <div class="fg-setting">
                <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'icon_color'} value={setting.global.icon_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_align')}</label>
            <div class="fg-setting">
                <vd-radio-btn-group name="icon_align" value={setting.global.icon_align} options={store.getOptions('blocks.button.icon_aligns')} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_icon_only_hover')}</label>
            <div class="fg-setting">
                <vd-switcher name="display_icon_hover" value="{setting.global.display_icon_hover}" evchange={change}/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color_hover')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_hover'} value={setting.global.color_hover} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_color_text_hover')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_text_hover'} value={setting.global.color_text_hover} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_border_color_hover')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'border_color_hover'} value={setting.global.border_color_hover} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_alignment')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="alignment" value={setting.global.alignment} options={store.getOptions('blocks.button.aligns')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_full_width')}</label>
        <div class="fg-setting">
            <vd-switcher name="full_width" value="{setting.global.full_width}" evchange={change}/>
        </div>
    </div>
    <div id="custom-width" hide={setting.global.full_width}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.button.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" value="{setting.global.width}" class="form-control pixels"  onChange={change}/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.button.entry_height')}</label>
        <div class="fg-setting">
            <input type="text" name="height" value="{setting.global.height}" class="form-control pixels"  onChange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-button>
<vd-setting-block-circle_bar>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_units')}</label>
        <div class="fg-setting">
            <input type="text" name="units" class="form-control" value="{setting.global.units}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_bar_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'bar_color'} value={setting.global.bar_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_track_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'track_color'} value={setting.global.track_color} evchange={change}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_line_width')}</label>
        <div class="fg-setting">
            <vd-range name="line_width" value={setting.global.line_width} evchange={change}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.circle_bar.entry_size')}</label>
        <div class="fg-setting">
            <vd-range name="size" value={setting.global.size} evchange={change} max='200'/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-circle_bar>
<vd-setting-block-code>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.code.entry_code')}</label>
        <div class="fg-setting">
            <textarea name="code" class="form-control" onchange={change}>{setting.edit.code}</textarea>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.setting.edit[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-code>
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
<vd-setting-block-column>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_size')}</label>
        <div class="fg-setting">
            <select name="size" class="form-control" onChange={change}>
                <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.size}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_offset')}</label>
        <div class="fg-setting">
            <select name="offset" class="form-control" onChange={change}>
                <option value="" selected={setting.global.offset == ''}>{store.getLocal('blocks.column.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.offset}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_order')}</label>
        <div class="fg-setting">
            <select name="order" class="form-control" onChange={change}>
                <option value="" selected={setting.global.order == ''}>{store.getLocal('blocks.column.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column.orders')} value="{key}" selected={key == setting.global.order}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_adaptive_design')}</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.column.column_device')}</td>
                    <td>{store.getLocal('blocks.column.column_offset')}</td>
                    <td>{store.getLocal('blocks.column.column_order')}</td>
                    <td>{store.getLocal('blocks.column.column_size')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.column.text_tablet')}</td>
                    <td>
                        <select name="offset_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_tablet == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.offset_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="order_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.order_tablet == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.orders')} value="{key}" selected={key == setting.global.order_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_tablet == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.size_tablet}>{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.column.text_phone')}</td>
                    <td>
                        <select name="offset_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_phone == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.offset_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="order_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.order_phone == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.orders')} value="{key}" selected={key == setting.global.order_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_phone == ''}>{store.getLocal('blocks.column.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column.sizes')} value="{key}" selected={key == setting.global.size_phone}>{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column.entry_float')}</label>
        <div class="fg-setting">
            <vd-switcher name="float" value="{setting.global.float}" evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.column.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.column.aligns')} evchange={change}/>
        </div>
    </div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }
    </script>
</vd-setting-block-column>
<vd-setting-block-column_inner>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_size')}</label>
        <div class="fg-setting">
            <select name="size" class="form-control" onChange={change}>
                <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.size}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_offset')}</label>
        <div class="fg-setting">
            <select name="offset" class="form-control" onChange={change}>
                <option value="" selected={setting.global.offset == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.offset}>{value}</option>
            </select>
        </div>
    </div>
        <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_order')}</label>
        <div class="fg-setting">
            <select name="order" class="form-control" onChange={change}>
                <option value="" selected={setting.global.order == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                <option each={value, key in store.getOptions('blocks.column_inner.orders')} value="{key}" selected={key == setting.global.order}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_adaptive_design')}</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.column_inner.column_device')}</td>
                    <td>{store.getLocal('blocks.column_inner.column_offset')}</td>
                    <td>{store.getLocal('blocks.column.column_order')}</td>
                    <td>{store.getLocal('blocks.column_inner.column_size')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.column_inner.text_tablet')}</td>
                    <td>
                        <select name="offset_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_tablet == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.offset_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="order_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.order_tablet == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.orders')} value="{key}" selected={key == setting.global.order_tablet}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_tablet" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_tablet == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.size_tablet}>{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.column_inner.text_phone')}</td>
                    <td>
                        <select name="offset_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.offset_phone == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.offset_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="order_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.order_phone == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.orders')} value="{key}" selected={key == setting.global.order_phone}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select name="size_phone" class="form-control" onChange={change}>
                            <option value="" selected={setting.global.size_phone == ''}>{store.getLocal('blocks.column_inner.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.column_inner.sizes')} value="{key}" selected={key == setting.global.size_phone}>{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_float')}</label>
        <div class="fg-setting">
            <vd-switcher name="float" value="{setting.global.float}" evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.column_inner.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.column_inner.aligns')} evchange={change}/>
        </div>
    </div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }
    </script>
</vd-setting-block-column_inner>
<vd-setting-block-conversion>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_type')}</label>
        <div class="fg-setting">
        <select class="form-control" name="type_conversion" onChange={change}>
            <option each={value, key in store.getOptions('blocks.conversion.types')} value="{key}" selected={setting.global.type_conversion == key}>{value}</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_selector_link')}</label>
        <div class="fg-setting">
            <input type="text" name="selector_link" class="form-control" value="{setting.edit.selector_link}" onChange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })

        if(_.isEmpty(this.setting.global.description_id)){

            this.setting.global.description_id = Math.random().toString(36).substring(2, 9)

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-conversion>
<vd-setting-block-countdown>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.conversion.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
    <label class="control-label">{store.getLocal('blocks.countdown.entry_datetime')}</label>
        <div class="fg-setting">
            <vd-datetime-picker name="datetime" value={setting.global.datetime} format="YYYY-MM-DD HH:mm" placeholder="{store.getLocal('blocks.countdown.entry_datetime')}" evchange={change}/>

    </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeText}/>
            <formatted-message path="blocks.countdown.text_formatter"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown.entry_final_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'final_text'} value={setting.edit.final_text} evchange={changeText}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-countdown>
<vd-setting-block-countdown_flip>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_counter_mode')}</label>
        <div class="fg-setting">
            <select class="form-control" name="counter_mode" onChange={change}>
                <option each={value, key in store.getOptions('blocks.countdown_flip.modes')} value="{key}" selected={setting.global.counter_mode == key}>{value}</option>
            </select>
        </div>
    </div>
    <div id="vd-specify-day" hide={setting.global.counter_mode != 'specify_date'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_datetime')}</label>
            <div class="fg-setting">
                <vd-datetime-picker name="datetime" value={setting.global.datetime} format="YYYY-MM-DD" time={false} placeholder="{store.getLocal('blocks.countdown_flip.entry_datetime')}" evchange={change}/>
            </div>
        </div>
    </div>
    <div id="vd-loop"  hide={setting.global.counter_mode != 'loop'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_time')}</label>
            <div class="fg-setting">
                <vd-datetime-picker name="time" value={setting.global.time} format="HH:mm" date={false} placeholder="{store.getLocal('blocks.countdown_flip.entry_time')}" evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_day_week')}</label>
            <div class="fg-setting">
                <select class="form-control" name="day_week" onChange={change}>
                    <option each={value, key in store.getOptions('blocks.countdown_flip.days_week')} value="{key}" selected={setting.global.day_week == key}>{value}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_display_day')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_day" value="{setting.global.display_day}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_display_hour')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_hour" value="{setting.global.display_hour}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_display_second')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_second" value="{setting.global.display_second}" evchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_display_title')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_title" value="{setting.global.display_title}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_color_number')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_number'} value={setting.global.color_number} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_color_title')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_title'} value={setting.global.color_title} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_background')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'background'} value={setting.global.background} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_scale')}</label>
        <div class="fg-setting">
            <input type="text" name="scale" class="form-control" value="{setting.global.scale}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.countdown_flip.entry_position')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="position" value={setting.global.position} options={store.getOptions('blocks.countdown_flip.positions')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-countdown_flip>
<vd-setting-block-custom_heading>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_text')}</label>
        <div class="fg-setting">
            <textarea class="form-control" name="text" onChange={change}>{setting.edit.text}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_tag')}</label>
        <div class="fg-setting">
            <select class="form-control" name="tag" onChange={change}>
                <option value="{value}" selected={value == setting.global.tag} each={value in store.getOptions('blocks.custom_heading.tags')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_font_family')}</label>
        <div class="fg-setting">
            <vd-font-picker name='font_family' value="{setting.global.font_family}" evchange={change}/>
            <vd-switcher name="underline" value="{setting.global.underline}" evchange={change} label={"<i class='fa fa-underline' aria-hidden='true'></i>"}/>
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change} label={"<i class='fa fa-bold' aria-hidden='true'></i>"}/>
            <vd-switcher name="italic" value="{setting.global.italic}" evchange={change} label={"<i class='fa fa-italic' aria-hidden='true'></i>"}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_line_height')}</label>
        <div class="fg-setting range">
            <vd-range name="line_height" value={setting.global.line_height} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.custom_heading.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.custom_heading.aligns')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])) {
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])) {
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-custom_heading>
<vd-setting-block-empty_space>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.empty_space.entry_height')}</label>
        <div class="fg-setting">
            <input type="text" name="height" value="{setting.global.height}" class="form-control pixels" onchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-empty_space>
<vd-setting-block-facebook_like>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_like.entry_type')}</label>
        <div class="fg-setting">
            <select class="form-control" name="type_button" onChange={change}>
                <option value="{key}" selected={key == setting.global.type_button} each={value, key in store.getOptions('blocks.facebook_like.types')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-facebook_like>
<vd-setting-block-facebook_widget>
    <div class="form-group">
    <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_href')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="href" value="{setting.global.href}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_height')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="height" value="{setting.global.height}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_width')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="width" value="{setting.global.width}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_tabs')}</label>
        <div class="fg-setting">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox" each={value, key in store.getOptions('blocks.facebook_widget.tabs')}>
                    <label>
                        <input type="checkbox" name="tabs" value="{key}" checked={_.indexOf(_.values(setting.global.tabs), key) != -1} onChange={changeCheckbox}/> {value}
                    </label>
                </div>
            </div>
            <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">{store.getLocal('blocks.facebook_widget.text_select_all')}</a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">{store.getLocal('blocks.facebook_widget.text_unselect_all')}</a>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_show_facepile')}</label>
        <div class="fg-setting">
            <vd-switcher name="show_facepile" value="{setting.global.show_facepile}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.facebook_widget.entry_small_header')}</label>
        <div class="fg-setting">
            <vd-switcher name="small_header" value="{setting.global.small_header}" evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeCheckbox(e) {
            if(e.target.checked) {
                if(!_.isEmpty(this.setting.global[e.target.name])){
                    var newIndex = _.max(_.map(_.keys(this.setting.global[e.target.name]), function(value){ return Number(value)}))+1;
                } else {
                    newIndex = 0
                }
                
                this.setting.global[e.target.name][newIndex] = e.target.value
            } else {
                this.setting.global[e.target.name] = _.pick(this.setting.global[e.target.name], function(value, key){ return value != e.target.value; });
            }

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-facebook_widget>
<vd-setting-block-features>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeText} onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_image')}</label>
        <div class="fg-setting">
            <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title="" width="100px" height="100px"/></a>
            <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_alt')}</label>
        <div class="fg-setting">
            <input type="text" name="image_alt" class="form-control" value="{setting.global.image_alt}"  onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="image_title" class="form-control" value="{setting.global.image_title}"  onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option each={value, key in store.getOptions('blocks.features.sizes')} value="{key}" selected={setting.global.size == key}>{value}</option>
            </select>
        </div>
    </div>
    <div id="size" hide={setting.global.size != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.features.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" class="form-control pixels" value="{setting.global.width}"  onchange={change}>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.features.entry_height')}</label>
            <div class="fg-setting">
                <input type="text" name="height" class="form-control pixels" value="{setting.global.height}"  onchange={change}>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.features.entry_image_position')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="image_position" value={setting.global.image_position} options={store.getOptions('blocks.features.image_positions')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-features>
<vd-setting-block-feedback>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.feedback.entry_additional_inputs')}</label>
        <div class="fg-setting">
            <table id="feedback_fields" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{store.getLocal('blocks.feedback.entry_name')}</td>
                        <td class="text-left">{store.getLocal('blocks.feedback.entry_value')}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr id="field-row{key}" each={field, key in setting.global.fields}>
                        <td class="text-left">
                            <div class="input-group" each={language in store.getOptions('blocks.feedback.languages')}>
                                <span class="input-group-addon"><img src="{language.flag}" title="{language.name}" /></span>
                                <input type="text" name="{key}.name.{language.language_id}" placeholder="{store.getLocal('blocks.feedback.entry_name')}" class="form-control" value="{field.name[language.language_id]}" onChange={changeField}/>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="{key}.value" class="form-control" value="{field.value}" placeholder="{store.getLocal('blocks.feedback.entry_value')}" onChange={changeField}/>
                        </td>
                        <td class="text-left">
                            <button type="button" data-toggle="tooltip" onClick={removeField} title="{store.getLocal('blocks.feedback.button_remove')}" class="btn btn-danger">
                                <i class="fa fa-minus-circle"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-left"><button type="button" onclick={addField} data-toggle="tooltip" title="{store.getLocal('blocks.feedback.button_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.feedback.entry_mode')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="mode" value={setting.global.mode} options={store.getOptions('blocks.feedback.modes')} evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="feedback-code" hide={setting.global.mode != 'code'}>
        <label class="control-label">{store.getLocal('blocks.feedback.entry_code')}</label>
        <div class="fg-setting">
            <textarea class="form-control" rows="5" name="code" onChange={change}>{setting.global.code}</textarea>
        </div>
    </div>
    <div class="form-group" id="feedback-file" hide={setting.global.mode != 'download_file'}>
        <label class="control-label">{store.getLocal('blocks.feedback.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control"name="link" value="{setting.global.link}" onChange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addField(e) {
            var field_setting = {value: ''};
            var languages = this.store.getOptions('blocks.feedback.languages')
            field_setting.name = {}
            for(var key in languages) {
                field_setting.name[languages[key].language_id] = ''
            }
            if(_.isEmpty(this.setting.global.fields)) {
                this.setting.global.fields = {0: field_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.fields), function(value){return Number(value)})) + 1
                this.setting.global.fields[new_index] = field_setting
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeField(e) {
            this.setting.global.fields = _.pick(this.setting.global.fields, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeField(e) {
            recompose(this.setting.global.fields, e.target.name, e.target.value)
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        function recompose(obj,is, value) {
            if (typeof is == 'string')
                return recompose(obj,is.split('.'), value);
            else if (is.length==1 && value!==undefined)
                return obj[is[0]] = value;
            else if (is.length==0)
                return obj;
            else
                return recompose(obj[is[0]],is.slice(1), value);
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-feedback>
<vd-setting-block-file>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.file.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" value="{setting.global.title}" class="form-control" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.file.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.file.entry_icon')}</label>
        <div class="fg-setting">
            <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.file.entry_icon_size')}</label>
        <div class="fg-setting">
            <vd-range name="icon_size" value={setting.global.icon_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.file.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.file.entry_filename')}</label>
        <div class="fg-setting">
            <div class="input-group">
                <input type="text" name="filename" value="{setting.edit.filename}" class="form-control" />
                <span class="input-group-btn">
                    <button type="button" id="button-upload" onclick={upload} data-loading-text="{store.getLocal('blocks.file.text_loading')}" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                </span>
            </div>
        </div>
    </div>
    <form enctype="multipart/form-data" style="display: none;"><input type="file" name="file" onChange="{changeFile}" /></form>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeFile(e) {
            $.ajax({
                    url: this.store.getOptions('blocks.file.upload_url'),
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('form', this.root)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    context: this,
                    beforeSend: function() {
                        $('#button-upload', this.root).button('loading');
                    },
                    complete: function() {
                        $('#button-upload', this.root).button('reset');
                    },
                    success: function(json) {
                        if (json['success']) {
                            this.setting.edit.filename = json.filename
                            this.setting.global.download_id = json.download_id
                            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
                            this.update()
                        }
                    }
                });
        }.bind(this)

        upload(e){
            $('form input[name=\'file\']', this.root).trigger('click');
        }.bind(this)

        change(e) {
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-file>
<vd-setting-block-gallery>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gallery.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gallery.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.gallery.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="size" style="display:none;">
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.gallery.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" class="form-control pixels" value="{setting.global.width}" onchange={change}/  >
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.gallery.entry_height')}</label>
            <div class="fg-setting">
                <input type="text" name="height" class="form-control pixels" value="{setting.global.height}" onchange={change} />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gallery.entry_type')}</label>
        <div class="fg-setting">
            <select class="form-control" name="type_gallery" onChange={change}>
                <option value="{key}" selected={key == setting.global.type_gallery} each={value, key in store.getOptions('blocks.gallery.types_gallery')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group type_gallery type_gallery_slider_slide type_gallery_slider_fade" >
        <label class="control-label">{store.getLocal('blocks.gallery.entry_auto_rotate')}</label>
        <div class="fg-setting">
            <input type="text" name="auto_rotate" class="form-control" value="{setting.global.auto_rotate}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gallery.entry_auto_rotate')}</label>
        <div class="fg-setting">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{store.getLocal('blocks.gallery.entry_additional_image')}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                        <tr id="image-row{key}" each={image, key in setting.edit.images}>
                            <td class="text-left">

                                <a href="" id="thumb-vd-image{key}" data-toggle="vd-image" class="img-thumbnail">
                                    <img src="{image.thumb}" alt="" title="" data-placeholder="{store.getOptions('blocks.gallery.placeholder')}" />
                                </a>

                                <input type="hidden" name="images[{key}]" value="{image.url}" id="input-vd-image{key}" onchange={changeImage} />
                            </td>
                            <td class="text-left"><button type="button" onclick={removeImage} data-toggle="tooltip" title="{store.getLocal('blocks.gallery.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-left"><button type="button" onclick={addImage} data-toggle="tooltip" title="{store.getLocal('blocks.gallery.button_image_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gallery.entry_onclick')}</label>
        <div class="fg-setting">
            <select class="form-control" name="onclick" onChange={change}>
                <option value="{key}" selected={key == setting.global.onclick} each={value, key in store.getOptions('blocks.gallery.actions')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="link" hide={setting.global.onclick != 'link'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.gallery.entry_link')}</label>
            <div class="fg-setting">
                <input type="text" name="link" class="form-control" value="{setting.global.link}" onchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.gallery.entry_link_target')}</label>
            <div class="fg-setting">
                <select class="form-control" name="link_target" onChange={change}>
                    <option value="new" selected={'new' == setting.global.link_target}>{store.getLocal('blocks.gallery.text_new_window')}</option>
                    <option value="current" selected={'current' == setting.global.link_target}>{store.getLocal('blocks.gallery.text_current_window')}</option>
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
            var field_setting = {thumb: this.store.getOptions('blocks.gallery.placeholder'), url:''};
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
</vd-setting-block-gallery>
<vd-setting-block-gmaps>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gmaps.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gmaps.entry_link')}</label>
        <div class="fg-setting">
            <textarea class="form-control" name="link" onchange={change}>{setting.global.link}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.gmaps.entry_height')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control pixels" name="height" value="{setting.global.height}" onchange={change} />
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-gmaps>
<vd-setting-block-google_plus_like>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_like.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.google_plus_like.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-google_plus_like>
<vd-setting-block-google_plus_widget>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_href')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="href" value="{setting.global.href}"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_type')}</label>
        <div class="fg-setting">
            <select class="form-control" name="badge_type" onChange={change}>
                <option each={value, key in store.getOptions('blocks.google_plus_widget.types')} value="{key}" selected={setting.global.badge_type == key}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_width')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="width" value="{setting.global.width}" / onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_showcoverphoto')}</label>
        <div class="fg-setting">
            <vd-switcher name="showcoverphoto" value="{setting.global.showcoverphoto}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_showtagline')}</label>
        <div class="fg-setting">
            <vd-switcher name="showtagline" value="{setting.global.showtagline}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_layout')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="layout" value={setting.global.layout} options={store.getOptions('blocks.google_plus_widget.layouts')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.google_plus_widget.entry_theme')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="theme" value={setting.global.theme} options={store.getOptions('blocks.google_plus_widget.themes')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-google_plus_widget>
<vd-setting-block-icon>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_icon')}</label>
        <div class="fg-setting">
            <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_background_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="background_style" onChange={change}>
                <option value="{key}" selected={key == setting.global.background_style} each={value, key in store.getOptions('blocks.icon.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_background_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'background_color'} value={setting.global.background_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.icon.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.icon.aligns')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_adaptive_design')}</label>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.icon.column_device')}</td>
                    <td>{store.getLocal('blocks.icon.column_align')}</td>
                    <td>{store.getLocal('blocks.icon.column_size')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.icon.text_tablet')}</td>
                    <td class="text-center">
                        <select class="form-control" name="align_tablet" onChange={change}>
                            <option value="" selected={'' == setting.global.align_tablet}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.align_tablet} each={value, key in store.getOptions('blocks.icon.aligns')}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="size_tablet" onChange={change}>
                            <option value="" selected={'' == setting.global.size_tablet}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.size_tablet} each={value, key in store.getOptions('blocks.icon.sizes')}>{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.icon.text_phone')}</td>
                    <td class="text-center">
                        <select class="form-control" name="align_phone" onChange={change}>
                            <option value="" selected={'' == setting.global.align_phone}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.align_phone} each={value, key in store.getOptions('blocks.icon.aligns')}>{value}</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="size_phone" onChange={change}>
                            <option value="" selected={'' == setting.global.size_phone}>{store.getLocal('blocks.icon.text_none')}</option>
                            <option value="{key}" selected={key == setting.global.size_phone} each={value, key in store.getOptions('blocks.icon.sizes')}>{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.icon.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}"  onChange={change} />
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-icon>
<vd-setting-block-image>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_title')}</label>
    <div class="fg-setting">
        <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_image')}</label>
    <div class="fg-setting">
        <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title="" width="100px" height="100px"/></a>
        <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image"  onChange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_alt')}</label>
    <div class="fg-setting">
        <input type="text" name="image_alt" class="form-control" value="{setting.global.image_alt}" onChange={change}>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_title')}</label>
    <div class="fg-setting">
        <input type="text" name="image_title" class="form-control" value="{setting.global.image_title}" onChange={change}>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_size')}</label>
    <div class="fg-setting">
        <select class="form-control" name="size" onChange={change}>
        <option each={value, key in store.getOptions('blocks.image.sizes')} value="{key}" selected={setting.global.size == key}>{value}</option>
        </select>
    </div>
</div>
<div id="size" hide={setting.global.size != 'custom'}>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control pixels" value="{setting.global.width}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_height')}</label>
        <div class="fg-setting">
            <input type="text" name="height" class="form-control pixels" value="{setting.global.height}" onChange={change}>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_style')}</label>
    <div class="fg-setting">
        <select class="form-control" name="style" onChange={change}>
            <option each={value, key in store.getOptions('blocks.image.styles')} value="{key}" selected={setting.global.style == key} selected="selected">{value}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_onclick')}</label>
    <div class="fg-setting">
        <select class="form-control" name="onclick" onChange={change}>
            <option each={value, key in store.getOptions('blocks.image.actions')} value="{key}" selected={setting.global.onclick == key} selected="selected">{value}</option>
        </select>
    </div>
</div>
<div id="link" hide={setting.global.onclick != 'link'}>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_link_target')}</label>
        <div class="fg-setting">
            <select class="form-control" name="link_target" onChange={change}>
                <option value="new" selected={setting.global.link_target == 'new'}>{store.getLocal('blocks.image.text_new_window')}</option>
                <option value="current" selected={setting.global.link_target == 'current'}>{store.getLocal('blocks.image.text_current_window')}</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_align')}</label>
    <div class="fg-setting">
        <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.image.aligns')} evchange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_adaptive_design')}</label>

    <table class="table table-bordered">
        <thead>
            <tr>
                <td>{store.getLocal('blocks.image.column_device')}</td>
                <td>{store.getLocal('blocks.image.column_align')}</td>
                <td>{store.getLocal('blocks.image.column_size')}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{store.getLocal('blocks.image.text_tablet')}</td>
                <td class="text-center">
                    <select class="form-control" name="align_tablet" onChange={change}>
                        <option value="" selected={setting.global.align_tablet == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.aligns')} value="{key}" selected={setting.global.align_tablet == key} selected="selected">{value}</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="size_tablet" onChange={change}>
                        <option value="" selected={setting.global.size_tablet == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.sizes')} value="{key}" selected={setting.global.size_tablet == key} selected="selected">{value}</option>
                    </select>
                    <div id="size_tablet" style="margin:20px 0px 0px 0px !important;" hide={setting.global.size_tablet != 'custom'}>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_width')}</label>
                            <div class="fg-setting">
                                <input type="text" name="width_tablet" class="form-control pixels" value="{setting.global.width_tablet}" onChange={change}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_height')}</label>
                            <div class="fg-setting">
                                <input type="text" name="height_tablet" class="form-control pixels" value="{setting.global.height_tablet}" onChange={change}>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>{store.getLocal('blocks.image.text_phone')}</td>
                <td class="text-center">
                    <select class="form-control" name="align_phone" onChange={change}>
                        <option value="" selected={setting.global.align_phone == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.aligns')} value="{key}" selected={setting.global.align_phone == key} selected="selected">{value}</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="size_phone" onChange={change}>
                        <option value="" selected={setting.global.size_phone == ''}>{store.getLocal('blocks.image.text_none')}</option>
                        <option each={value, key in store.getOptions('blocks.image.sizes')} value="{key}" selected={setting.global.size_phone == key} selected="selected">{value}</option>
                    </select>
                    <div id="size_phone" style="margin:0px 0px 20px 0px !important;" hide={setting.global.size_phone != 'custom'}>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_width')}</label>
                            <div class="fg-setting">
                                <input type="text" name="width_phone" class="form-control pixels" value="{setting.global.width_phone}" onChange={change}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{store.getLocal('blocks.image.entry_height')}</label>
                            <div class="fg-setting">
                                <input type="text" name="height_phone" class="form-control pixels" value="{setting.global.height_phone}" onChange={change}>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.image.entry_parallax')}</label>
    <div class="fg-setting">
        <vd-switcher name="parallax" value="{setting.global.parallax}" evchange={change}/>
    </div>
</div>
<div id="parallax" hide={!setting.global.parallax}>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image.entry_parallax_height')}</label>
        <div class="fg-setting">
            <input type="text" name="parallax_height" class="form-control pixels" value="{setting.global.parallax_height}" onChange={change}/>
        </div>
    </div>
</div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.level = this.parent.level
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(e){
        this.setting.global[e.target.name] = e.target.value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
    }
</script>
</vd-setting-block-image>
<vd-setting-block-image_caption>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeSummernote}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_image')}</label>
        <div class="fg-setting">
            <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title=""/></a>
            <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_alt')}</label>
        <div class="fg-setting">
            <input type="text" name="image_alt" class="form-control" value="{setting.global.image_alt}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="image_title" class="form-control" value="{setting.global.image_title}" onchange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="size" onChange={change}>
                <option value="{key}" selected={key == setting.global.size} each={value, key in store.getOptions('blocks.image_caption.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="size" hide={setting.global.size != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.image_caption.entry_width')}</label>
            <div class="fg-setting">
                <input type="text" name="width" class="form-control pixels" value="{setting.global.width}" onChange={change}>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.image_caption.entry_height')}</label>
            <div class="fg-setting">
                <input type="text" name="height" class="form-control pixels" value="{setting.global.height}" onChange={change}>
            </div>
        </div>
    </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.image_caption.entry_onclick')}</label>
            <div class="fg-setting">
                <select class="form-control" name="onclick" onChange={change}>
                    <option value="{key}" selected={key == setting.global.onclick} each={value, key in store.getOptions('blocks.image_caption.actions')}>{value}</option>
                </select>
            </div>
        </div>
        <div id="link" hide={setting.global.onclick != 'link'}>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.image_caption.entry_link')}</label>
                <div class="fg-setting">
                    <input type="text" name="link" class="form-control" value="{setting.global.link}" onchange={change}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.image_caption.entry_link_target')}</label>
                <div class="fg-setting">
                    <select class="form-control" name="link_target" onChange={change}>
                        <option value="new" selected={'new' == setting.global.link_target}>{store.getLocal('blocks.image_caption.text_new_window')}</option>
                        <option value="current" selected={'current' == setting.global.link_target}>{store.getLocal('blocks.image_caption.text_current_window')}</option>
                    </select>
                </div>
            </div>
        </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_display_border')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_border" value="{setting.global.display_border}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_padding_text')}</label>
        <div class="fg-setting">
            <input type="text" name="padding_text" class="form-control pixels" value="{setting.global.padding_text}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_position_text')}</label>
        <div class="fg-setting">
            <select class="form-control" name="position_text" onChange={change}>
                <option value="{key}" selected={key == setting.global.position_text} each={value, key in store.getOptions('blocks.image_caption.positions')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.image_caption.entry_animate')}</label>
        <div class="fg-setting">
            <select class="form-control" name="animate" onChange={change}>
                <option value="{key}" selected={key == setting.global.animate} each={value, key in store.getOptions('blocks.image_caption.animates')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        changeSummernote(name, value){
            this.setting.global[name] = value
            this.setting.edit[name] = value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        
    </script>
</vd-setting-block-image_caption>
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
<vd-setting-block-line_chart>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.line_chart.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.line_chart.entry_x_values')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="x_values" value="{setting.global.x_values}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.line_chart.entry_values')}</label>
        <div class="fg-setting">
            <table id="bar-values" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td class="text-left">{store.getLocal('blocks.line_chart.entry_label')}</td>
                    <td class="text-left">{store.getLocal('blocks.line_chart.entry_value')}</td>
                    <td class="text-left">{store.getLocal('blocks.line_chart.entry_color')}</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr id="value-row" each={value, key in setting.global.values}>
                    <td class="text-left">
                        <input type="text" class="form-control" name="{key}.label"  value="{value.label}" onChange={changeValue}/>
                    </td>
                    <td class="text-left">
                        <input type="text" class="form-control" name="{key}.value"  value="{value.value}" onChange={changeValue}/>
                    </td>
                    <td class="text-left">
                        <vd-color-picker name={key+'.color'} value={value.color} evchange={changeValue}/>
                    </td>
                    <td class="text-left"><button type="button" onclick={removeValue} data-toggle="tooltip" title="{store.getLocal('blocks.line_chart.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-left"><button type="button" onclick={addValue} data-toggle="tooltip" title="{store.getLocal('blocks.line_chart.button_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.line_chart.entry_modes')}</label>
        <div class="fg-setting">
            <select class="form-control" name="mode" onChange={change}>
                <option value="{key}" selected={key == setting.global.mode} each={value, key in store.getOptions('blocks.line_chart.modes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.line_chart.entry_display_legend')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_legend" value="{setting.global.display_legend}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.line_chart.entry_animate')}</label>
        <div class="fg-setting">
            <select class="form-control" name="animatemode" onChange={change}>
                <option value="{key}" selected={key == setting.global.animate} each={value, key in store.getOptions('blocks.line_chart.animates')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addValue(e) {
            var value_setting = {label: '', value: '', color: ''};

            if(_.isEmpty(this.setting.global.values)) {
                this.setting.global.values = {0: value_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.values), function(value){return Number(value)})) + 1
                this.setting.global.values[new_index] = value_setting
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeValue(e) {
            this.setting.global.values = _.pick(this.setting.global.values, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeValue(e) {
            recompose(this.setting.global.values, e.target.name, e.target.value)
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        function recompose(obj,is, value) {
            if (typeof is == 'string')
                return recompose(obj,is.split('.'), value);
            else if (is.length==1 && value!==undefined)
                return obj[is[0]] = value;
            else if (is.length==0)
                return obj;
            else
                return recompose(obj[is[0]],is.slice(1), value);
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-line_chart>
<vd-setting-block-mailchimp>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_api')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="api" value="{setting.global.api}" onchange={change}/>
        </div>
        <br/>
        <div class="alert alert-info"> <formatted-message path="blocks.mailchimp.help_api"/></div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_lists')}</label>
        <div class="fg-setting">
            <div class="input-group">
                <select class="form-control" name="list_id" onChange={change}>
                    <option value="{key}" selected={key == setting.global.list_id} each={value, key in setting.edit.lists}>{value}</option>
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="button_load_lists" onclick={updateList}>{store.getLocal('blocks.mailchimp.button_refresh')}</button>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_display_firstname')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_firstname" value="{setting.global.display_firstname}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_display_lastname')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_lastname" value="{setting.global.display_lastname}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_inline')}</label>
        <div class="fg-setting">
            <vd-switcher name="inline" value="{setting.global.inline}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_final_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'final_text'} value={setting.edit.final_text} evchange={changeText}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_background_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_background_color'} value={setting.global.input_background_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_color_text'} value={setting.global.input_color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_border_color'} value={setting.global.input_border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_focus_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_focus_border_color'} value={setting.global.input_focus_border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="input_border_width" value={setting.global.input_border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_border_radius')}</label>
        <div class="fg-setting">
            <vd-range name="input_border_radius" value={setting.global.input_border_radius} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="input_font_size" value={setting.global.input_font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_width')}</label>
        <div class="fg-setting">
            <input type="text" name="input_width" value="{setting.global.input_width}" class="form-control pixels-procent" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_input_height')}</label>
        <div class="fg-setting">
            <input type="text" name="input_height" value="{setting.global.input_height}" class="form-control pixels-procent" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_title')}</label>
        <div class="fg-setting">
            <input type="text" name="button_title" value="{setting.global.button_title}" class="form-control" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="button_style" onChange={change}>
                <option value="{value}" selected={value == setting.global.button_style} each={value in store.getOptions('blocks.mailchimp.button_styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="vd-custom-style-button" hide={setting.global.button_style != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_background_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_background_color'} value={setting.global.button_background_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_color_text'} value={setting.global.button_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_hover_background_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_background_color'} value={setting.global.button_hover_background_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_hover_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_color_text'} value={setting.global.button_hover_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_border_color'} value={setting.global.button_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_hover_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_border_color'} value={setting.global.button_hover_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_border_width')}</label>
            <div class="fg-setting range">
                <vd-range name="button_border_width" value={setting.global.button_border_width} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_border_radius')}</label>
            <div class="fg-setting">
                <vd-range name="button_border_radius" value={setting.global.button_border_radius} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_font_size')}</label>
            <div class="fg-setting">
                <vd-range name="button_font_size" value={setting.global.button_font_size} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_width')}</label>
            <div class="fg-setting">
                <input type="text" name="button_width" value="{setting.global.button_width}" class="form-control pixels" onchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailchimp.entry_button_height')}</label>
            <div class="fg-setting">
                <input type="text" name="button_height" value="{setting.global.button_height}" class="form-control pixels" onchange={change}/>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        updateList(e) {
            $.ajax({
                url: 'index.php?route=extension/d_visual_designer_module/mailchimp/getList&'+this.store.getOptions('blocks.mailchimp.url_token'),
                type: "POST",
                dataType: "json",
                context: this,
                data: 'api=' + this.setting.global.api,
                success: function(json) {
                    if (json['success']) {
                        this.setting.edit.lists = json.lists
                        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
                        this.update()
                    }
                }
            });
            }.bind(this)
    </script>
</vd-setting-block-mailchimp>
<vd-setting-block-mailerlite>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_api')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="api" value="{setting.global.api}" onchange={change}/>
        </div>
        <br/>
        <div class="alert alert-info"> <formatted-message path="blocks.mailerlite.help_api"/></div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_lists')}</label>
        <div class="fg-setting">
            <div class="input-group">
                <select class="form-control" name="list_id" onChange={change}>
                    <option value="{key}" selected={key == setting.global.list_id} each={value, key in setting.edit.lists}>{value}</option>
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="button_load_lists" onclick={updateList}>{store.getLocal('blocks.mailerlite.button_refresh')}</button>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_display_firstname')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_firstname" value="{setting.global.display_firstname}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_display_lastname')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_lastname" value="{setting.global.display_lastname}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_inline')}</label>
        <div class="fg-setting">
            <vd-switcher name="inline" value="{setting.global.inline}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_final_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'final_text'} value={setting.edit.final_text} evchange={changeText}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_background_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_background_color'} value={setting.global.input_background_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_color_text'} value={setting.global.input_color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_border_color'} value={setting.global.input_border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_focus_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'input_focus_border_color'} value={setting.global.input_focus_border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="input_border_width" value={setting.global.input_border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_border_radius')}</label>
        <div class="fg-setting">
            <vd-range name="input_border_radius" value={setting.global.input_border_radius} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="input_font_size" value={setting.global.input_font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_width')}</label>
        <div class="fg-setting">
            <input type="text" name="input_width" value="{setting.global.input_width}" class="form-control pixels-procent" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_input_height')}</label>
        <div class="fg-setting">
            <input type="text" name="input_height" value="{setting.global.input_height}" class="form-control pixels-procent" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_title')}</label>
        <div class="fg-setting">
            <input type="text" name="button_title" value="{setting.global.button_title}" class="form-control" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="button_style" onChange={change}>
                <option value="{value}" selected={value == setting.global.button_style} each={value in store.getOptions('blocks.mailerlite.button_styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div id="vd-custom-style-button" hide={setting.global.button_style != 'custom'}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_background_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_background_color'} value={setting.global.button_background_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_color_text'} value={setting.global.button_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_hover_background_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_background_color'} value={setting.global.button_hover_background_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_hover_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_color_text'} value={setting.global.button_hover_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_border_color'} value={setting.global.button_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_hover_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_hover_border_color'} value={setting.global.button_hover_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_border_width')}</label>
            <div class="fg-setting range">
                <vd-range name="button_border_width" value={setting.global.button_border_width} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_border_radius')}</label>
            <div class="fg-setting">
                <vd-range name="button_border_radius" value={setting.global.button_border_radius} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_font_size')}</label>
            <div class="fg-setting">
                <vd-range name="button_font_size" value={setting.global.button_font_size} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_width')}</label>
            <div class="fg-setting">
                <input type="text" name="button_width" value="{setting.global.button_width}" class="form-control pixels" onchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.mailerlite.entry_button_height')}</label>
            <div class="fg-setting">
                <input type="text" name="button_height" value="{setting.global.button_height}" class="form-control pixels" onchange={change}/>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        updateList(e) {
            $.ajax({
                url: 'index.php?route=extension/d_visual_designer_module/mailerlite/getList&'+this.store.getOptions('blocks.mailerlite.url_token'),
                type: "POST",
                dataType: "json",
                context: this,
                data: 'api=' + this.setting.global.api,
                success: function(json) {
                    if (json['success']) {
                        this.setting.edit.lists = json.lists

                        if(!_.isEmpty(json.lists)){
                            this.setting.global.list_id = _.keys(json.lists)[0]
                        }

                        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
                        this.update()
                    }
                }
            });
            }.bind(this)
    </script>
</vd-setting-block-mailerlite>
<vd-setting-block-message_box>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{key}" selected={key == setting.global.style} each={value, key in store.getOptions('blocks.message_box.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_share')}</label>
        <div class="fg-setting">
            <select class="form-control" name="share" onChange={change}>
                <option value="{key}" selected={key == setting.global.share} each={value, key in store.getOptions('blocks.message_box.shares')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'border_color'} value={setting.global.border_color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_icon')}</label>
        <div class="fg-setting">
            <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.message_box.entry_text')}</label>
        <div class="fg-setting">
            <vd-summernote name={'text'} value={setting.edit.text} evchange={changeSummernote}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        changeSummernote(name, value){
            this.setting.global[name] = value
            this.setting.edit[name] = value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-message_box>
<vd-setting-block-module>
    <div class="form-group">
    <label class="col-sm-2 control-label" for="input-row">{store.getLocal('blocks.code.entry_module')}</label>
    <div class="fg-setting">
        <select name="code" class="form-control" onchange={change}>     
            <optgroup each={extension in store.getOptions('blocks.module.extensions')} label="{extension.name}" >
                <option if={!_.isEmpty(extension.module)} each={module in extension.module} value="{module.code}" selected={module.code == setting.global.code}>{module.name}</option>
                <option if={_.isEmpty(extension.module)} value="{extension.code}" selected={extension.code == setting.global.code}>{extension.name}</option>
            </optgroup>
        </select>
    </div>
</div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-module>
<vd-setting-block-number>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_number')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="number" value="{setting.global.number}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_thousand_separator')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="thousand_separator" value="{setting.global.thousand_separator}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_duration')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="duration" value="{setting.global.duration}" onchange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_font_size')}</label>
        <div class="fg-setting">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_font_bold')}</label>
        <div class="fg-setting">
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.number.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-number>
<vd-setting-block-pinterest_like>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pinterest_like.entry_type_button')}</label>
        <div class="fg-setting">
            <select class="form-control" name="type_button" onChange={change}>
                <option value="{key}" selected={key == setting.global.type_button} each={value, key in store.getOptions('blocks.pinterest_like.types')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-pinterest_like>
<vd-setting-block-popup>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.popup.entry_active_corner')}</label>
        <div class="fg-setting">
            <select class="form-control" name="active_corner" onChange={change}>
                <option value="{key}" selected={key == setting.global.active_corner} each={value, key in store.getOptions('blocks.popup.corners')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.popup.entry_click_trigger')}</label>
        <div class="fg-setting">
            <vd-switcher name="click_trigger" value="{setting.global.click_trigger}" evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="setting-selector" hide={!setting.global.click_trigger}>
        <label class="control-label">{store.getLocal('blocks.popup.entry_selector')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="selector" value="{setting.global.selector}" onchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-popup>
<vd-setting-block-pricing_table>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table.entry_width_between')}</label>
        <div class="fg-setting">
            <input type="text" name="width_between" value="{setting.global.width_between}" class="form-control pixels" onchange={change}/>
        </div>
    </div>

    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-pricing_table>
<vd-setting-block-pricing_table_section>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_subtitle')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="subtitle" value="{setting.global.subtitle}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_background')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'background'} value={setting.global.background} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_color_text')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_text'} value={setting.global.color_text} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{key}" selected={key == setting.global.style} each={value, key in store.getOptions('blocks.pricing_table_section.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_currency')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="currency" value="{setting.global.currency}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_price')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="price" value="{setting.global.price}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_per')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="per" value="{setting.global.per}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_feautures')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="feautures" value="{setting.global.feautures}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_align_feauture')}</label>
        <div class="fg-setting">
            <select class="form-control" name="align_feauture" onChange={change}>
                <option value="{value}" selected={value == setting.global.align_feauture} each={value in store.getOptions('blocks.pricing_table_section.aligns')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_display_icon')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_icon" value="{setting.global.display_icon}" evchange={change}/>
        </div>
    </div>
    <div id="icon-setting" hide={!setting.global.display_icon}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_icon_library')}</label>
            <div class="fg-setting">
                <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_icon')}</label>
            <div class="fg-setting">
                <vd-icon-picker name={'icon'} value={setting.global.icon} evchange={change} library={setting.global.library}/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_display_button')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_button" value="{setting.global.display_button}" evchange={change}/>
        </div>
    </div>
    <div id="button-setting" hide={!setting.global.display_button}>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_text')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="button_text" value="{setting.global.button_text}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_link')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control" name="button_link" value="{setting.global.button_link}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_style')}</label>
            <div class="fg-setting">
                <select class="form-control" name="button_style" onChange={change}>
                    <option value="{value}" selected={value == setting.global.button_style} each={value in store.getOptions('blocks.pricing_table_section.button_styles')}>{value}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_background')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_background'} value={setting.global.button_background} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_color_text')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_color_text'} value={setting.global.button_color_text} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_border_width')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control pixels" name="button_border_width" value="{setting.global.button_border_width}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_border_color')}</label>
            <div class="fg-setting">
                <vd-color-picker name={'button_border_color'} value={setting.global.button_border_color} evchange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_padding_top')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control pixels" name="button_padding_top" value="{setting.global.button_padding_top}" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('blocks.pricing_table_section.entry_button_padding_bottom')}</label>
            <div class="fg-setting">
                <input type="text" class="form-control pixels" name="button_padding_bottom" value="{setting.global.button_padding_bottom}" onChange={change}/>
            </div>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-pricing_table_section>
<vd-setting-block-product>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.product.entry_product')}</label>
        <div class="fg-setting">
            <vd-autocomplete name="product_id" value={setting.global.product_id} url={store.getOptions('blocks.product.autocomplete')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.product.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{key}" selected={key == setting.global.style} each={value, key in store.getOptions('blocks.product.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-product>
<vd-setting-block-products_grid>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_width')}</label>
        <div class="fg-setting">
            <select class="form-control" name="width" onChange={change}>
                <option value="{key}" selected={key == setting.global.width} each={value, key in store.getOptions('blocks.products_grid.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_width_tablet')}</label>
        <div class="fg-setting">
            <select class="form-control" name="width_tablet" onChange={change}>
                <option value="{key}" selected={key == setting.global.width_tablet} each={value, key in store.getOptions('blocks.products_grid.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_width_phone')}</label>
        <div class="fg-setting">
            <select class="form-control" name="width_phone" onChange={change}>
                <option value="{key}" selected={key == setting.global.width_phone} each={value, key in store.getOptions('blocks.products_grid.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_mode')}</label>
        <div class="fg-setting">
            <select class="form-control" name="mode" onChange={change}>
                <option value="{key}" selected={key == setting.global.mode} each={value, key in store.getOptions('blocks.products_grid.modes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group" hide={setting.global.mode != 'custom_products'}>
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_products')}</label>
        <div class="fg-setting">
            <vd-autocomplete-well 
                name="products" 
                value={setting.global.products} 
                options={setting.edit.products}
                url={store.getOptions('blocks.products_grid.autocomplete_products')} 
                evchange={change}/>
        </div>
    </div>
    <div class="form-group" hide={setting.global.mode != 'custom_categories'}>
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_categories')}</label>
        <div class="fg-setting">
            <vd-autocomplete-well 
                name="categories" 
                value={setting.global.categories} 
                options={setting.edit.categories}
                url={store.getOptions('blocks.products_grid.autocomplete_categories')} 
                evchange={change}/>
        </div>
    </div>
    <div class="form-group" hide={setting.global.mode == 'custom_products'}>
        <label class="control-label">{store.getLocal('blocks.products_grid.entry_count_product')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="count_product" value="{setting.global.count_product}" onChange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-products_grid>
<vd-setting-block-products_slider>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_count')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="count" value="{setting.global.count}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_interval')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="interval" value="{setting.global.interval}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_mode')}</label>
        <div class="fg-setting">
            <select class="form-control" name="mode" onChange={change}>
                <option value="{key}" selected={key == setting.global.mode} each={value, key in store.getOptions('blocks.products_slider.modes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group" hide={setting.global.mode != 'custom_products'}>
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_products')}</label>
        <div class="fg-setting">
            <vd-autocomplete-well 
                name="products" 
                value={setting.global.products} 
                options={setting.edit.products}
                url={store.getOptions('blocks.products_slider.autocomplete_products')} 
                evchange={change}/>
        </div>
    </div>
    <div class="form-group" hide={setting.global.mode != 'custom_categories'}>
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_categories')}</label>
        <div class="fg-setting">
            <vd-autocomplete-well 
                name="categories" 
                value={setting.global.categories} 
                options={setting.edit.categories}
                url={store.getOptions('blocks.products_slider.autocomplete_categories')} 
                evchange={change}/>
        </div>
    </div>
    <div class="form-group" hide={setting.global.mode == 'custom_products'}>
        <label class="control-label">{store.getLocal('blocks.products_slider.entry_count_product')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="count_product" value="{setting.global.count_product}" onChange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-products_slider>
<vd-setting-block-progress_bar>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.progress_bar.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.progress_bar.entry_values')}</label>
        <div class="fg-setting">
            <table id="bar-values" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{store.getLocal('blocks.progress_bar.entry_label')}</td>
                        <td class="text-left">{store.getLocal('blocks.progress_bar.entry_value')}</td>
                        <td class="text-left">{store.getLocal('blocks.progress_bar.entry_color')}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                        <tr each={value, key in setting.global.values}>
                            <td class="text-left">
                                <input type="text" class="form-control" name="{key}.label"  value="{value.label}" onchange={changeValue} />
                            </td>
                            <td class="text-left">
                                <input type="text" class="form-control" name="{key}.value"  value="{value.value}" onchange={changeValue} />
                            </td>
                            <td class="text-left">
                                <vd-color-picker name={key+'.color'} value={value.color} evchange={changeValue}/>
                            </td>
                            <td class="text-left"><button type="button" onclick={removeValue} data-toggle="tooltip" title="{store.getLocal('blocks.progress_bar.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left"><button type="button" onclick={addValue} data-toggle="tooltip" title="{store.getLocal('blocks.progress_bar.button_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.progress_bar.entry_units')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="units" value="{setting.global.units}" onchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.progress_bar.entry_animate')}</label>
        <div class="fg-setting">
            <vd-switcher name="animate" value="{setting.global.animate}" evchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.progress_bar.entry_stripes')}</label>
        <div class="fg-setting">
            <vd-switcher name="stripes" value="{setting.global.stripes}" evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addValue(e) {
            var value_setting = {label: '', value: '', color: ''};

            if(_.isEmpty(this.setting.global.values)) {
                this.setting.global.values = {0: value_setting}
            } else {
                var new_index = Number(_.max(_.keys(this.setting.global.values))) + 1
                this.setting.global.values[new_index] = value_setting
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeValue(e) {
            this.setting.global.values = _.pick(this.setting.global.values, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeValue(e) {
            recompose(this.setting.global.values, e.target.name, e.target.value)
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        function recompose(obj,is, value) {
            if (typeof is == 'string')
                return recompose(obj,is.split('.'), value);
            else if (is.length==1 && value!==undefined)
                return obj[is[0]] = value;
            else if (is.length==0)
                return obj;
            else
                return recompose(obj[is[0]],is.slice(1), value);
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-progress_bar>
<vd-setting-block-round_chart>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_values')}</label>
        <div class="fg-setting">
                        <table id="bar-values" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-left">{store.getLocal('blocks.round_chart.entry_label')}</td>
                        <td class="text-left">{store.getLocal('blocks.round_chart.entry_value')}</td>
                        <td class="text-left">{store.getLocal('blocks.round_chart.entry_color')}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                        <tr each={value, key in setting.global.values}>
                            <td class="text-left">
                                <input type="text" class="form-control" name="{key}.label"  value="{value.label}" onchange={changeValue} />
                            </td>
                            <td class="text-left">
                                <input type="text" class="form-control" name="{key}.value"  value="{value.value}" onchange={changeValue} />
                            </td>
                            <td class="text-left">
                                <vd-color-picker name={key+'.color'} value={value.color} evchange={changeValue}/>
                            </td>
                            <td class="text-left"><button type="button" onclick={removeValue} data-toggle="tooltip" title="{store.getLocal('blocks.round_chart.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-left"><button type="button" onclick={addValue} data-toggle="tooltip" title="{store.getLocal('blocks.round_chart.button_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_modes')}</label>
        <div class="fg-setting">
            <select class="form-control" name="mode" onChange={change}>
                <option value="{key}" selected={key == setting.global.mode} each={value, key in store.getOptions('blocks.round_chart.modes')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_gap')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="stroke_width" value="{setting.global.stroke_width}"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_display_legend')}</label>
        <div class="fg-setting">
            <vd-switcher name="display_legend" value="{setting.global.display_legend}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.round_chart.entry_animate')}</label>
        <div class="fg-setting">
            <select class="form-control" name="animate" onChange={change}>
                <option value="{key}" selected={key == setting.global.animate} each={value, key in store.getOptions('blocks.round_chart.animates')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addValue(e) {
            var value_setting = {label: '', value: '', color: ''};

            if(_.isEmpty(this.setting.global.values)) {
                this.setting.global.values = {0: value_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.values), function(value){return Number(value)})) + 1
                this.setting.global.values[new_index] = value_setting
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeValue(e) {
            this.setting.global.values = _.pick(this.setting.global.values, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeValue(e) {
            recompose(this.setting.global.values, e.target.name, e.target.value)
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        function recompose(obj,is, value) {
            if (typeof is == 'string')
                return recompose(obj,is.split('.'), value);
            else if (is.length==1 && value!==undefined)
                return obj[is[0]] = value;
            else if (is.length==0)
                return obj;
            else
                return recompose(obj[is[0]],is.slice(1), value);
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-round_chart>
<vd-setting-block-row>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_row_stretch')}</label>
    <div class="fg-setting">
        <select class="form-control" name="row_stretch" onChange={change}>
            <option each={value, key in store.getOptions('blocks.row.stretchs')} value="{key}" selected={key == setting.global.row_stretch}>{value}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_background_video')}</label>
    <div class="fg-setting">
        <vd-switcher name="background_video" value="{setting.global.background_video}" evchange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_video_link')}</label>
    <div class="fg-setting">
        <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row.entry_container')}</label>
    <div class="fg-setting">
        <vd-radio-btn-group name="container" value={setting.global.container} options={store.getOptions('blocks.row.containers')} evchange={change}/>
    </div>
</div>
<div class="form-group" id="align">
    <label class="control-label">{store.getLocal('blocks.row.entry_align')}</label>
    <div class="fg-setting">
        <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.row.aligns')} evchange={change}/>
    </div>
</div>
<div class="form-group" id="align">
    <label class="control-label">{store.getLocal('blocks.row.entry_align_items')}</label>
    <div class="fg-setting">
        <select name="align_items" class="form-control" onChange={change}>
            <option each={value, key in store.getOptions('blocks.row.align_items')} value="{key}" selected={setting.global.align_items == key}>{value}</option>
        </select>
    </div>
</div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.level = this.parent.level
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(e){
        this.setting.global[e.target.name] = e.target.value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
    }
</script>
</vd-setting-block-row>
<vd-setting-block-row_inner>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.row.entry_background_video')}</label>
        <div class="fg-setting">
            <vd-switcher name="background_video" value="{setting.global.background_video}" evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.row.entry_video_link')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
    <label class="control-label">{store.getLocal('blocks.row_inner.entry_container')}</label>
    <div class="fg-setting">
        <vd-radio-btn-group name="container" value={setting.global.container} options={store.getOptions('blocks.row_inner.containers')} evchange={change}/>
    </div>
</div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.row_inner.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.row_inner.aligns')} evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.row_inner.entry_align_items')}</label>
        <div class="fg-setting">
            <select name="align_items" class="form-control" onChange={change}>
                <option each={value, key in store.getOptions('blocks.row_inner.align_items')} value="{key}" selected={setting.global.align_items == key}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-row_inner>
<vd-setting-block-search_products>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.search_products.entry_title')}</label>
        <div class="fg-setting">
             <input type="text" class="form-control" name="title" value="{setting.global.title}" onChange={change}>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-search_products>
<vd-setting-block-section>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.section.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}"  onchange={change}/>
        </div>
    </div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }
    </script>
</vd-setting-block-section>
<vd-setting-block-section_accordion>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.section_accordion.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onchange={change}>
        </div>
    </div>
    <script>
        this.top = this.parent ? this.parent.top : this
        this.level = this.parent.level
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        }
    </script>
</vd-setting-block-section_accordion>
<vd-setting-block-section_wrapper>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.section_wrapper.entry_row_stretch')}</label>
    <div class="fg-setting">
        <select class="form-control" name="row_stretch" onChange={change}>
            <option each={value, key in store.getOptions('blocks.section_wrapper.stretchs')} value="{key}" selected={key == setting.global.section_wrapper_stretch}>{value}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.section_wrapper.entry_background_video')}</label>
    <div class="fg-setting">
        <vd-switcher name="background_video" value="{setting.global.background_video}" evchange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.section_wrapper.entry_video_link')}</label>
    <div class="fg-setting">
        <input type="text" class="form-control" name="link" value="{setting.global.link}" onChange={change}/>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.section_wrapper.entry_container')}</label>
    <div class="fg-setting">
        <vd-radio-btn-group name="container" value={setting.global.container} options={store.getOptions('blocks.section_wrapper.containers')} evchange={change}/>
    </div>
</div>
<script>
    this.top = this.parent ? this.parent.top : this
    this.level = this.parent.level
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(e){
        this.setting.global[e.target.name] = e.target.value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
    }
</script>
</vd-setting-block-section_wrapper>
<vd-setting-block-separater>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.separater.entry_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.separater.entry_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="border_width" value={setting.global.border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.separater.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control percents" value="{setting.global.width}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.separater.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.separater.aligns')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.separater.entry_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{value}" selected={value == setting.global.style} each={value in store.getOptions('blocks.separater.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-separater>
<vd-setting-block-social_icons>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_icon_library')}</label>
        <div class="fg-setting">
            <vd-select-icon-library name="library" value={setting.global.library} evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_type')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="icon_type" value={setting.global.icon_type} options={store.getOptions('blocks.social_icons.types')} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_margin_beetwen')}</label>
        <div class="fg-setting range">
            <vd-range name="margin_beetwen" value={setting.global.margin_beetwen} evchange={change}/>
        </div>
    </div>
    <div class="form-group" id="align">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.social_icons.aligns')} evchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.social_icons.entry_adaptive_design')}</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{store.getLocal('blocks.social_icons.column_device')}</td>
                    <td>{store.getLocal('blocks.social_icons.column_margin_beetwen')}</td>
                    <td>{store.getLocal('blocks.social_icons.column_align')}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{store.getLocal('blocks.column.text_tablet')}</td>
                    <td>
                        <input type="text" name="margin_beetwen_tablet" class="form-control pixels" value={setting.global.margin_beetwen_tablet} onchange={change}/>
                    </td>
                    <td>
                        <select class="form-control" name="align_tablet" onChange={change}>
                            <option value="" selected={setting.global.align_tablet == ''}>{store.getLocal('blocks.image.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.social_icons.aligns')} value="{key}" selected={setting.global.align_tablet == key} selected="selected">{value}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{store.getLocal('blocks.column.text_phone')}</td>
                    <td>
                        <input type="text" name="margin_beetwen_phone" class="form-control pixels" value={setting.global.margin_beetwen_phone} onchange={change}/>
                    </td>
                    <td>
                        <select class="form-control" name="align_phone" onChange={change}>
                            <option value="" selected={setting.global.align_phone == ''}>{store.getLocal('blocks.image.text_none')}</option>
                            <option each={value, key in store.getOptions('blocks.social_icons.aligns')} value="{key}" selected={setting.global.align_phone == key} selected="selected">{value}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <vd-collapse tabs={setting.global.icons}>
        <vd-collapse-item each={tab_setting, tab in opts.tabs} title="{store.getLocal('blocks.social_icons.text_'+tab)}">
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_icon')}</label>
                <div class="fg-setting">
                    <vd-icon-picker name="{tab}.icon" value={tab_setting.icon} evchange={top.changeIcon} library={top.setting.global.library}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_link')}</label>
                <div class="fg-setting">
                    <input type="text" class="form-control" name="{tab}.link" value={tab_setting.link} onChange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_size')}</label>
                <div class="fg-setting range">
                    <vd-range name="{tab}.size" value={tab_setting.size} evchange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group" if={top.setting.global.icon_type != 'text'}>
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_background')}</label>
                <div class="fg-setting">
                    <vd-color-picker name="{tab}.background" value={tab_setting.background} evchange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_color')}</label>
                <div class="fg-setting">
                    <vd-color-picker name="{tab}.color" value={tab_setting.color} evchange={top.changeIcon}/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{store.getLocal('blocks.social_icons.entry_sort_order')}</label>
                <div class="fg-setting">
                    <input type="text" class="form-control" name="{tab}.sort_order" value={tab_setting.sort_order} onChange={top.changeIcon}/>
                </div>
            </div>
        </vd-collapse-item>
    </vd-collapse>
    <script>
        this.mixin({store:d_visual_designer})
        this.active = ''
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeIcon(e) {
            var names = e.target.name.split('.')
            this.setting.global.icons[names[0]][names[1]] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-social_icons>
<vd-setting-block-star_rating>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.star_rating.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.star_rating.entry_rating')}</label>
        <div class="fg-setting">
            <select class="form-control" name="rating" onChange={change}>
                <option value="{value}" selected={value == setting.global.rating} each={value in store.getOptions('blocks.star_rating.ratings')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.star_rating.entry_size')}</label>
        <div class="fg-setting">
            <select class="form-control" name="star_size" onChange={change}>
                <option value="{key}" selected={key == setting.global.star_size} each={value, key in store.getOptions('blocks.star_rating.sizes')}>{value}</option>
            </select>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-star_rating>
<vd-setting-block-tabs>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.tabs.entry_active_section')}</label>
        <div class="fg-setting">
            <input class="form-control" name="active_section" value="{setting.global.active_section}" onchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.tabs.entry_title')}</label>
        <div class="fg-setting">
            <input class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.tabs.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.tabs.aligns')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-tabs>
<vd-setting-block-testimonial>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.testimonial.entry_image')}</label>
        <div class="fg-setting">
            <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail"><img src="{setting.edit.thumb}" alt="" title="" width="100px" height="100px"/></a>
            <input type="hidden" name="image" value="{setting.global.image}" id="input-vd-image"  onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.testimonial.entry_name')}</label>
        <div class="fg-setting">
            <input type="text" class="form-control" name="name" value="{setting.global.name}" onChange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.testimonial.entry_comment')}</label>
        <div class="fg-setting">
            <vd-summernote name={'comment'} value={setting.edit.comment} evchange={changeText} onchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        changeText(name, value) {
            this.setting.global[name] = value
            if(!_.isUndefined(this.setting.edit[name])){
                this.setting.edit[name] = value
            }
            if(!_.isUndefined(this.setting.user[name])){
                this.setting.user[name] = value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-testimonial>
<vd-setting-block-text>
<div class="form-group">
    <label class="control-label">{store.getLocal('blocks.text.entry_text')}</label>
    <div class="fg-setting">
        <vd-summernote name={'text'} value={setting.edit.text} evchange={change}/>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.setting = this.opts.block.setting
    this.on('update', function(){
        this.setting = this.opts.block.setting
    })
    change(name, value){
        this.setting.global[name] = value
        this.setting.user[name] = value
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
        this.update()
    }
</script>
</vd-setting-block-text>
<vd-setting-block-text_separater>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_title_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="title_align" value={setting.global.title_align} options={store.getOptions('blocks.text_separater.aligns')} evchange={change}/>
        </div>
    </div>
    <div id="font-size" class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_font_size')}</label>
        <div class="fg-setting range">
            <vd-range name="font_size" value={setting.global.font_size} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_font_family')}</label>
        <div class="fg-setting">
            <vd-font-picker name='font_family' value="{setting.global.font_family}" evchange={change}/>
            <vd-switcher name="underline" value="{setting.global.underline}" evchange={change} label={"<i class='fa fa-underline' aria-hidden='true'></i>"}/>
            <vd-switcher name="bold" value="{setting.global.bold}" evchange={change} label={"<i class='fa fa-bold' aria-hidden='true'></i>"}/>
            <vd-switcher name="italic" value="{setting.global.italic}" evchange={change} label={"<i class='fa fa-italic' aria-hidden='true'></i>"}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_color_title')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_title'} value={setting.global.color_title} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_border_width')}</label>
        <div class="fg-setting">
            <vd-range name="border_width" value={setting.global.border_width} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_border_style')}</label>
        <div class="fg-setting">
            <select class="form-control" name="style" onChange={change}>
                <option value="{value}" selected={value == setting.global.style} each={value in store.getOptions('blocks.text_separater.styles')}>{value}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_border_color')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color'} value={setting.global.color} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control percents" value="{setting.global.width}" onChange={change}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.text_separater.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.text_separater.aligns')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-text_separater>
<vd-setting-block-tour>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.tour.entry_active_section')}</label>
        <div class="fg-setting">
            <input class="form-control" name="active_section" value="{setting.global.active_section}" onchange={change}/>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.tour.entry_title')}</label>
        <div class="fg-setting">
            <input class="form-control" name="title" value="{setting.global.title}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.tour.entry_align')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="align" value={setting.global.align} options={store.getOptions('blocks.tour.aligns')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-tour>
<vd-setting-block-trust>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.trust.entry_title')}</label>
        <div class="fg-setting">
            <input type="text" name="title" class="form-control" value="{setting.global.title}" onChange={change}>
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <td class="text-left">{store.getLocal('blocks.trust.column_image')}</td>
                <td class="text-left">{store.getLocal('blocks.trust.column_link')}</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr each={image, key in setting.global.images}>
                <td class="text-left">
                    <select class="form-control" name="{key}.image" onChange={changeImage}>
                        <option value="{value}" selected={value == image.image} each={value, key in setting.edit.images}>{key}</option>
                    </select>
                </td>
                <td class="text-left">
                    <input type="text" name="{key}.link" value="{image.link}" class="form-control" onChange={changeImage}/>
                </td>
                <td class="text-left"><button type="button" onclick={removeImage} data-toggle="tooltip" title="{store.getLocal('blocks.trust.button_remove')}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="text-left"><button type="button" onclick={addImage} data-toggle="tooltip" title="{store.getLocal('blocks.trust.button_image_add')}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
            </tr>
        </tfoot>
    </table>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        addImage(e) {
            var image_setting = {image: '', link: ''};
            if(_.isEmpty(this.setting.global.images)) {
                this.setting.global.images = {0: image_setting}
            } else {
                var new_index = _.max(_.map(_.keys(this.setting.global.images), function(value){return Number(value)})) + 1
                this.setting.global.images[new_index] = image_setting
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        removeImage(e) {
            this.setting.global.images = _.pick(this.setting.global.images, function(value, key) {
                return Number(key) !== Number(e.item.key)
            })

            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }.bind(this)
        changeImage(e) {
            recompose(this.setting.global.images, e.target.name, e.target.value)
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
        function recompose(obj,is, value) {
            if (typeof is == 'string')
                return recompose(obj,is.split('.'), value);
            else if (is.length==1 && value!==undefined)
                return obj[is[0]] = value;
            else if (is.length==0)
                return obj;
            else
                return recompose(obj[is[0]],is.slice(1), value);
        }
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-trust>
<vd-setting-block-twitter_like>
    <div></div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.setting.edit[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-twitter_like>
<vd-setting-block-twitter_widget>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_href')}</label>
        <div class="fg-setting">
            <input class="form-control" type="text" name="href" value="{setting.global.href}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_width')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="width" value="{setting.global.width}" onChange={change} />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_height')}</label>
        <div class="fg-setting">
            <input class="form-control pixels" type="text" name="height" value="{setting.global.height}" onChange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_color_link')}</label>
        <div class="fg-setting">
            <vd-color-picker name={'color_link'} value={setting.global.color_link} evchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.twitter_widget.entry_theme')}</label>
        <div class="fg-setting">
            <vd-radio-btn-group name="theme" value={setting.global.theme} options={store.getOptions('blocks.twitter_widget.themes')} evchange={change}/>
        </div>
    </div>
    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            if(!_.isUndefined(this.setting.edit[e.target.name])){
                this.setting.edit[e.target.name] = e.target.value
            }
            if(!_.isUndefined(this.setting.user[e.target.name])){
                this.setting.user[e.target.name] = e.target.value
            }
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-twitter_widget>
<vd-setting-block-video>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.video.entry_link')}</label>
        <div class="fg-setting">
            <input type="text" name="link" class="form-control" value="{setting.global.link}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.video.entry_width')}</label>
        <div class="fg-setting">
            <input type="text" name="width" class="form-control percents" value="{setting.global.width}" onchange={change}/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{store.getLocal('blocks.video.entry_ratio')}</label>
        <div class="fg-setting">
            <select class="form-control" name="ratio" onChange={change}>
                <option value="{key}" selected={key == setting.global.ratio} each={value, key in store.getOptions('blocks.video.ratios')}>{value}</option>
            </select>
        </div>
    </div>

    <script>
        this.mixin({store:d_visual_designer})
        this.setting = this.opts.block.setting
        this.on('update', function(){
            this.setting = this.opts.block.setting
        })
        change(e){
            this.setting.global[e.target.name] = e.target.value
            this.store.dispatch('block/setting/fastUpdate', {designer_id: this.parent.designer_id, block_id: this.opts.block.id, setting: this.setting})
            this.update()
        }
    </script>
</vd-setting-block-video>
