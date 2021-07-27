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