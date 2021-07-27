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