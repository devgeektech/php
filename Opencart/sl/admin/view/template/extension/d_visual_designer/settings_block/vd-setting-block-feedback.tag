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