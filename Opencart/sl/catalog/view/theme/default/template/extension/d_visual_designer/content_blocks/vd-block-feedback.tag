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