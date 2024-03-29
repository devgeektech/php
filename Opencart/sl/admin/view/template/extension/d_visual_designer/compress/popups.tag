<vd-popup-codeview>
<div class="vd vd-popup-overlay" if={this.status}></div>
<div class="vd vd-popup add_template" if={this.status} style="max-height:75vh;">
    <div class="popup-header">
        <h2 class="title">{store.getLocal('designer.text_codeview')}</h2>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-content">
        <div class="popup-codeview">
            <textarea name="codeview" class="text-codeview form-control" onChange={change}>{content}</textarea>
        </div>
    </div>
    <div class="popup-footer">
        <a id="save-codeview" class="vd-btn save" onClick={save}>{store.getLocal('designer.button_save')}</a>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.designer_id = this.parent.opts.id
    this.content = this.store.getState().content[this.designer_id]
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''

    this.store.subscribe('content/codeview/success', function(data){
        if(this.designer_id == data.designer_id) {
            this.content = this.store.getState().content[this.designer_id]
            this.status = true
            this.update()
        }
    }.bind(this))

    this.store.subscribe('content/codeview/update/success', function(data){
        if(this.designer_id == data.designer_id) {
            this.status = false
            this.update()
        }
    }.bind(this))

    save(e){
        this.store.dispatch('content/update', {designer_id: this.designer_id, content: this.content, post_action: ['content/codeview/update/success']})
    }.bind(this)

    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            start: function(){
                $('body').addClass('vd-resizable')
            },
            resize: function(event, ui) {
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
                $('body').removeClass('vd-resizable')
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });
    }.bind(this)

    this.on('updated', function(){
        if(this.status) {
            this.initPopup()
        }
    })

    change(e) {
        this.content = e.target.value
    }

    close() {
        this.status = false
    }
</script>
</vd-popup-codeview>
<vd-popup-image-manager>
<div class="vd vd-popup image-manager" if={this.status} style="max-height:75vh;">
    <div class="popup-header">
        <h2 class="title">{store.getLocal('designer.text_file_manager')}</h2>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-content">
        <div class="popup-image-manager">
            <iframe src="{store.getState().config.filemanager_url}&field={input_id}&thumb={element_id}" frameborder="no" scrolling="no"></iframe>
        </div>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.designer_id = this.parent.opts.id
    this.input_id = ''
    this.element_id = ''
    this.left = ''
    this.top = ''

    this.store.subscribe('popup/image-manager/show', function(data){
        if(this.designer_id == data.designer_id) {
            this.status = true
            this.input_id = data.input_id
            this.element_id = data.element_id
            this.update()
        }
    }.bind(this))
    this.store.subscribe('popup/image-manager/hide', function(data){
        if(this.designer_id == data.designer_id) {
            this.status = false
            this.input_id = ''
            this.element_id = ''
            this.update()
        }
    }.bind(this))

    this.initPopup = function() {
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });
    }.bind(this)

    this.on('updated', function(){
        if(this.status) {
            this.initPopup()
        }
    })

    close() {
        this.status = false
    }
</script>
</vd-popup-image-manager>
<vd-popup-layout-block>
<div class="vd vd-popup-overlay" if={this.status}></div>
<div class="vd vd-popup edit-layout {classPopup}" if={this.status} style="max-height:75vh;">
    <div class="popup-header">
        <h2 class="title">{store.getLocal('designer.text_layout')}</h2>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
        <div data-is='vd-layout-block-{block_type}' block={block_info} designer_id={this.parent.opts.id} class="popup-content"></div>
     <div class="popup-footer">
        <a id="save" class="vd-btn save" onClick={save}>{store.getLocal('designer.button_save')}</a>
    </div>
    <image-manager/>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.block_id = ''
    this.classPopup = ''
    this.block_type = ''
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''
    this.block_config = _.find(this.store.getState().config.blocks, function(block){
        return block.type == this.block_type
    }.bind(this))
    this.block_info = ''
    this.previewColorChange = 0
    this.layoutSetting = {}

    save(e){
        this.store.dispatch('block/layout/update', _.extend({block_id: this.block_id, designer_id: this.parent.opts.id, type: this.block_config.type}, this.layoutSetting))
    }.bind(this)

    this.store.subscribe('block/layout/setting/update', function(data){

        this.layoutSetting = data
    }.bind(this))

    this.store.subscribe('block/layout/update/success', function(){
        this.status = false
        this.block_id = ''
        this.block_type = ''
        this.update()
    }.bind(this))

    this.store.subscribe('block/layout/begin', function(data){
        if(data.designer_id == this.parent.opts.id) {
            this.status = true
            this.block_id = data.block_id
            this.block_type = data.type
            this.update()
            this.initPopup()
        }
    }.bind(this))

    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            start: function(){
                $('body').addClass('vd-resizable')
            },
            resize: function(event, ui) {
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
                $('body').removeClass('vd-resizable')
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });
    }.bind(this)

    this.on('update', function(){
        if(this.block_type && this.block_id) {
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == this.block_type
            }.bind(this))
            this.block_info = this.store.getState().blocks[this.parent.opts.id][this.block_id]
            this.block_info.id = this.block_id
            if( this.block_info.parent == ''){
                this.classPopup = 'main'
            } else if (this.block_config.setting.child_blocks) {
                this.classPopup = 'inner'
            } else {
                this.classPopup = 'child'
            }
            this.setting = this.store.getState().blocks[this.parent.opts.id][this.block_id].setting
        }
    })

    close() {
        this.status = false
        this.block_id = ''
        this.block_type = ''
    }
</script>
</vd-popup-layout-block>
<vd-popup-load-template>
<div class="vd vd-popup-overlay" if={this.status}></div>
<div class="vd vd-popup add_template" if={this.status}>
    <div class="popup-header">
        <h2 class="title"><formatted-message path='designer.text_add_template'/></h2>
        <div class="search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" name="search" placeholder="{this.store.getLocal('designer.text_search')}" onInput={searchBlock} value="{search}"/>
        </div>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-tabs">
        <ul class="vd-nav">
            <li class="active"><a href="#tab-get-template" data-toggle="tab" onClick={categoryReset}><formatted-message path='designer.tab_all_blocks'/></a></li>
            <li each={category in categories}><a id="new-block-tab"  data-toggle="tab" onClick={categoryChange}>{category}</a></li>
        </ul>
    </div>
    <div class="popup-content">
        <div class="notify alert alert-warning" if={store.getState().config.notify}>
            <formatted-message path='designer.text_complete_version'/>
        </div>
        <div class="popup-new-template">
            <div class="element" each={template in templates}>
                <div class="template" onClick={addTemplate}>
                    <a id="add_block" name="type">
                        <img src="{template.image}" class="image">
                        <p class="title">{template.name}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.search = ''
    this.category = '*'
    this.level = 0
    this.target = ''
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''

    this.store.subscribe('template/list', function(data){
        if(data.designer_id == this.parent.top.opts.id){
            this.status = true
            this.level = data.level
            this.parent_id = data.parent_id
            this.update()
            this.initPopup()
        }
    }.bind(this))
    this.store.subscribe('template/load/success', function(){
        this.status = false
        this.update()
    }.bind(this))
    addTemplate(e) {
        this.store.dispatch('template/load', {config: e.item.template.config, designer_id:this.parent.top.opts.id, template_id: e.item.template.template_id})
    }
    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            start: function(){
                $('body').addClass('vd-resizable')
            },
            resize: function(event, ui) {
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
                $('body').removeClass('vd-resizable')
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });

    }.bind(this)

    this.on('update', function(){
        this.templates = []
        this.categories = []
        this.templates = this.store.getState().templates

        for(var key in this.templates) {
            if(this.categories.indexOf(this.templates[key].category) == -1) {
                this.categories.push(this.templates[key].category)
            }
        }
        if(this.category != '*') {
            this.templates = _.pick(this.templates, function(template){
                return template.category === this.category
            }.bind(this))
        }
        if(this.search != '') {
            this.templates = _.pick(this.templates, function(template){
                return template.name.toLowerCase().indexOf(this.search.toLowerCase()) != -1
            }.bind(this))
        }
    })

    categoryChange(e) {
        this.category = e.item.category
    }

    categoryReset(e) {
        this.category = '*'
    }

    searchBlock(e) {
        this.search = e.target.value
    }

    close() {
        this.status = false
    }
</script>
</vd-popup-load-template>
<vd-popup-new-block>
<div class="vd vd-popup-overlay" if={this.status}></div>
<div class="vd vd-popup add_block" if={this.status}>
    <div class="popup-header">
        <h2 class="title"><formatted-message path='designer.text_add_block'/></h2>
        <div class="search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" name="search" placeholder="{this.store.getLocal('designer.text_search')}" onInput={searchBlock} value="{search}"/>
        </div>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-tabs">
        <ul class="vd-nav">
            <li class="active"><a href="#tab-get-template" data-toggle="tab" onClick={categoryReset}><formatted-message path='designer.tab_all_blocks'/></a></li>
            <li each={category in categories}><a id="new-block-tab"  data-toggle="tab" onClick={categoryChange}>{category}</a></li>
        </ul>
    </div>
    <div class="popup-content">
        <div class="notify alert alert-warning" if={store.getState().config.notify}>
            <formatted-message path='designer.text_complete_version'/>
        </div>
        <div class="row popup-new-block">
            <div class="col-md-3 col-sm-6 col-xs-12 element" each={block in blocks}>
                <div class="block" onClick={addBlock}>
                    <a id="add_block" name="type">
                        <span><img src="{block.image}" class="image"></span>
                        {block.title}
                        <i class="description">
                           {block.description}
                        </i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.search = ''
    this.category = '*'
    this.level = 0
    this.target = ''
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''

    this.store.subscribe('popup/addBlock', function(data){
        if(data.designer_id == this.parent.top.opts.id){
            this.status = true
            this.level = data.level
            this.parent_id = data.parent_id
            this.update()
            this.initPopup()
        }
    }.bind(this))
    this.store.subscribe('block/create/success', function(){
        this.status = false
    }.bind(this))
    addBlock(e) {
        this.store.dispatch('block/new', {type: e.item.block.type, designer_id:this.parent.top.opts.id, target: this.parent_id, level: this.level})
    }
    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            start: function(){
                $('body').addClass('vd-resizable')
            },
            resize: function(event, ui) {
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
                 $('body').removeClass('vd-resizable')
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });

    }.bind(this)

    this.on('update', function(){
        this.blocks = []
        this.categories = []
        var items = this.store.getState().config.blocks

        this.blocks = _.pick(items, function(item){
             if(item.setting.level_min <= this.level && item.setting.level_max >= this.level) {
                 return true
             }
             if(this.level == 0 && item.setting.level_min == 3 && (item.setting.helper_insert || _.isUndefined(item.setting.helper_insert))){
                 return true
             }
             return false
        }.bind(this))
        for(var key in this.blocks) {
            if(this.categories.indexOf(this.blocks[key].category) == -1) {
                this.categories.push(this.blocks[key].category)
            }
        }
        if(this.category != '*') {
            this.blocks = _.pick(this.blocks, function(item){
                return item.category === this.category
            }.bind(this))
        }
        if(this.search != '') {
            this.blocks = _.pick(this.blocks, function(item){
                return item.title.toLowerCase().indexOf(this.search.toLowerCase()) != -1
            }.bind(this))
        }
    })

    categoryChange(e) {
        this.category = e.item.category
    }

    categoryReset(e) {
        this.category = '*'
    }

    searchBlock(e) {
        this.search = e.target.value
    }

    close() {
        this.status = false
    }
</script>
</vd-popup-new-block>
<vd-popup-save-template>
<div class="vd vd-popup-overlay" if={this.status}></div>
<div class="vd vd-popup save_template" if={this.status}>
    <div class="popup-header">
        <h2 class="title"><formatted-message path='designer.text_save_template'/></h2>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-content">
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_name')}</label>
            <div class="fg-setting">
                <input type="text" name="name" value="" placeholder="{store.getLocal('designer.entry_name')}" class="form-control" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_category')}</label>
            <div class="fg-setting">
                <input type="text" name="category" value="" placeholder="{store.getLocal('designer.entry_category')}" class="form-control" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_image_template')}</label>
            <div class="fg-setting">
                <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail">
                    <img src="{store.getOptions('designer.placeholder')}" alt="" title="" data-placeholder="{store.getOptions('designer.placeholder')}"/>
                </a>
                <input type="hidden" name="image" value="" id="input-vd-image" onChange={change}/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">{store.getLocal('designer.entry_sort_order')}</label>
            <div class="fg-setting">
                <input type="text" name="sort_order" value="" placeholder="{store.getLocal('designer.entry_sort_order')}" class="form-control" onChange={change}/>
            </div>
        </div>
    </div>
    <div class="popup-footer">
        <a class="vd-btn save" data-loading-text="{store.getLocal('designer.button_saved')}" onClick={saveTemplate}>{store.getLocal('designer.button_save')}</a>
    </div>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''

    this.setting = {
        name: '',
        category: '',
        image: '',
        sort_order: ''
    }

    this.store.subscribe('template/save/popup', function(data){
        if(data.designer_id == this.parent.top.opts.id){
            this.status = true
            this.update()
            this.initPopup()
        }
    }.bind(this))
    this.store.subscribe('template/save/success', function(){
        $('.vd-btn.save', this.root).button('reset')
        this.status = false
        this.update()
    }.bind(this))

    saveTemplate(e) {
        $('.vd-btn.save', this.root).button('loading')
        this.store.dispatch('template/save', {setting: this.setting, designer_id: this.parent.top.opts.id})
    }

    change(e) {
        this.setting[e.target.name] = e.target.value
    }

    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            start: function(){
                $('body').addClass('vd-resizable')
            },
            resize: function(event, ui) {
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    $('.vd-popup', this.root).addClass('drag')
                }
                
                $('.vd-popup', this.root).css({ 'max-height': '' });
            }.bind(this),
            stop: function( event, ui ) {
                this.width = ui.size.width;
                this.height = ui.size.height;
                $('body').removeClass('vd-resizable')
            }.bind(this)
        });
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if (!ui.helper.hasClass('drag')) {
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });

    }.bind(this)

    close() {
        this.status = false
    }
</script>
</vd-popup-save-template>
<vd-popup-setting-block>
<div class="vd vd-popup {classPopup} {stick_left? 'stick-left':''}" if={this.status} style="max-height:75vh;">
    <div class="popup-header">
        <h2 class="title">{block_config.title} {store.getLocal('designer.text_edit_block')}</h2>
        <a class="stick-left" onClick={stickPopup}></a>
        <a class="close" onClick={close}><i class="fal fa-times"></i></a>
    </div>
    <div class="popup-tabs">
        <ul class="vd-nav">
            <li class="active"><a href="#tab-edit-block" data-toggle="tab"><formatted-message path='designer.tab_general'/></a></li>
            <li><a href="#tab-design-block" data-toggle="tab"><formatted-message path='designer.tab_design'/></a></li>
            <li><a href="#tab-css-block" data-toggle="tab"><formatted-message path='designer.tab_css'/></a></li>
        </ul>
    </div>
    <div class="popup-content">
        <div class="tab-content body">
            <div class="tab-pane active" id="tab-edit-block">
                <div data-is='vd-setting-block-{block_type}' block={block_info} ></div>
            </div>
            <div class="tab-pane" id="tab-design-block">
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_margin')}</label>
                    <div class="fg-setting">
                        <div class="vd-icon-group">
                            <div class="vd-icon-group-content">
                                <vd-tab-nav tabs="{marginTabs}" if="{setting.global.design_margin_responsive}"/>
                                <vd-input-group block_id="{block_id}" designer_id="{designer_id}" name="design_margin" if="{!setting.global.design_margin_responsive}"/>
                            </div>
                            <div class="vd-icon-group-icon">
                                <div onclick="{responsiveMargin}" if="{!setting.global.design_margin_responsive}"><i class="fal fa-mobile"></i></div>
                                <div onclick="{cancelMargin}" if="{setting.global.design_margin_responsive}"><i class="fal fa-times"></i></div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_padding')}</label>
                    <div class="fg-setting">
                        <div class="vd-icon-group">
                            <div class="vd-icon-group-content">
                                <vd-tab-nav tabs="{paddingTabs}" if="{setting.global.design_padding_responsive}"/>
                                <vd-input-group block_id="{block_id}" designer_id="{designer_id}" name="design_padding" if="{!setting.global.design_padding_responsive}"/>
                            </div>
                            <div class="vd-icon-group-icon">
                                <div onclick="{responsivePadding}" if="{!setting.global.design_padding_responsive}"><i class="fal fa-mobile"></i></div>
                                <div onclick="{cancelPadding}" if="{setting.global.design_padding_responsive}"><i class="fal fa-times"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_border')}</label>
                    <div class="fg-setting">
                        <vd-input-group block_id="{block_id}" designer_id="{designer_id}" name="design_border"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_border_color')}</label>
                    <div class="fg-setting">
                        <vd-color-picker name={'design_border_color'} value={setting.global.design_border_color} evchange={change}/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_border_style')}</label>
                    <div class="fg-setting">
                        <select name="design_border_style" class="form-control"  onChange={change}>
                            <option each={value, key in store.getOptions('designer.border_styles')} value="{key}" selected={setting.global.design_border_style == key}>{value}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_border_radius')}</label>
                    <div class="fg-setting">
                        <input type="text" name="design_border_radius" class="form-control pixels" value="{setting.global.design_border_radius}" onChange={change}>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_animate')}</label>
                    <div class="fg-setting">
                        <select name="design_animate" class="form-control" onChange={change}>
                            <option each={value, key in store.getOptions('designer.animates')} value="{key}" selected={setting.global.design_animate == key}>{value}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_show_on')}</label>
                    <div class="fg-setting">
                        <label class="vd-checkbox">
                            <input type="checkbox" name="design_show_on" value="show_mobile" checked={_.contains(setting.global.design_show_on, 'show_mobile')} onChange={change}> {store.getLocal('designer.text_phone')}
                        </label>
                        <br>
                        <label class="vd-checkbox">
                            <input type="checkbox" name="design_show_on" value="show_tablet" checked={_.contains(setting.global.design_show_on, 'show_tablet')} onChange={change}> {store.getLocal('designer.text_tablet')}
                        </label>
                        <br>
                        <label class="vd-checkbox">
                            <input type="checkbox" name="design_show_on" value="show_desktop" checked={_.contains(setting.global.design_show_on, 'show_desktop')} onChange={change}> {store.getLocal('designer.text_desktop')}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_image')}</label>
                    <div class="fg-setting">
                        <a href="" id="thumb-vd-image" data-toggle="vd-image" class="img-thumbnail">
                            <img src="{setting.edit.design_background_thumb}" alt="" title="" data-placeholder="{store.getOptions('designer.placeholder')}" width="100px" height="100px" />
                        </a>
                        <input type="hidden" name="design_background_image" value="{setting.global.design_background_image}" id="input-vd-image" onChange={change} />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_image_style')}</label>
                    <div class="fg-setting">
                        <select name="design_background_image_style" class="form-control" onChange={change}>
                            <option each={value, key in store.getOptions('designer.image_styles')} value="{key}" selected={setting.global.design_background_image_style == key}>{value}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" hide={setting.global.design_background_image_style == 'parallax'}>
                    <label class="control-label">{store.getLocal('designer.entry_image_position')}</label>
                    <div class="fg-setting">
                        <div class="wrap-setting-wrapper">
                            <div class="wrap-setting">
                                <select name="design_background_image_position_horizontal" class="form-control" onChange={change}>
                                    <option each={value, key in store.getOptions('designer.image_horizontal_positions')} value="{key}" selected={setting.global.design_background_image_position_horizontal == key}>{value}</option>
                                </select>
                                <span class="label-helper">{store.getLocal('designer.text_horizontal')}</span>
                            </div>
                            <div class="wrap-setting">
                                <select name="design_background_image_position_vertical" class="form-control" onChange={change}>
                                    <option each={value, key in store.getOptions('designer.image_vertical_positions')} value="{key}" selected={setting.global.design_background_image_position_vertical == key}>{value}</option>
                                </select>
                                <span class="label-helper">{store.getLocal('designer.text_vertical')}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_background')}</label>
                    <div class="fg-setting">
                        <vd-color-picker name={'design_background'} value={setting.global.design_background} evchange={change}/>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-css-block">
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_id')}</label>
                    <div class="fg-setting">
                        <input type="text" name="id" class="form-control"  value="{setting.global.id}" onchange={change}/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_additional_css_class')}</label>
                    <div class="fg-setting">
                        <input type="text" name="additional_css_class" class="form-control" value="{setting.global.additional_css_class}" onChange={change}>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_additional_css_before')}</label>
                    <div class="fg-setting">
                        <textarea name="additional_css_before" class="form-control" onChange={change}>{setting.global.additional_css_before}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_additional_css_content')}</label>
                    <div class="fg-setting">
                        <textarea name="additional_css_content" class="form-control" onChange={change}>{setting.global.additional_css_content}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">{store.getLocal('designer.entry_additional_css_after')}</label>
                    <div class="fg-setting">
                        <textarea name="additional_css_after" class="form-control" onChange={change}>{setting.global.additional_css_after}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <div class="popup-footer">
        <div class="vd-btn-group btn-4">
            <a class="vd-btn cancel" onClick={cancel}><i class="fas fa-times"></i></a>
            <a class="vd-btn undo" onClick={undoHistory}><i class="fas fa-undo-alt"></i></a>
            <a class="vd-btn return" onClick={returnHistory}><i class="fas fa-redo-alt"></i></a>
            <a class="vd-btn apply" onClick={save}><i class="fas fa-check"></i></a>
        </div>
    </div>
    <image-manager designer_id={designer_id}/>
</div>
<script>
    this.mixin({store:d_visual_designer})
    this.status = false
    this.stick_left = false
    this.block_id = ''
    this.classPopup = ''
    this.block_type = ''
    this.width = ''
    this.height = ''
    this.left = ''
    this.top = ''
    this.designer_id = this.parent.opts.id
    this.block_config = _.find(this.store.getState().config.blocks, function(block){
        return block.type == this.block_type
    }.bind(this))
    this.block_info = ''
    this.previewColorChange = 0
    this.marginTabs = {}
    this.paddingTabs = {}

    save(e){
        this.store.dispatch('block/setting/update', {block_id: this.block_id, designer_id: this.designer_id})
        this.status = false;
        this.block_id = '';
        this.block_type = '';

        $('body').removeAttr('style');
        $(window).trigger('resize')
        this.update();
    }.bind(this)

    undoHistory(e) {
        this.store.dispatch('history/undo', {block_id: this.block_id, designer_id: this.designer_id, fast: true})
    }.bind(this)

    returnHistory(e) {
        this.store.dispatch('history/return', {block_id: this.block_id, designer_id: this.designer_id})
    }.bind(this)

    this.closePopup = function () {
        this.status = false
        this.block_id = ''
        this.block_type = ''

        $('body').removeAttr('style');
        $(window).trigger('resize')
        this.update();
    }.bind(this)

    this.store.subscribe('block/layout/begin', function(data){
        this.closePopup()
    }.bind(this))
    this.store.subscribe('template/save/popup', function(data){
        this.closePopup()
    }.bind(this))
    this.store.subscribe('template/list', function(data) {
        this.closePopup()
    }.bind(this))
    this.store.subscribe('popup/addBlock', function(data) {
        this.closePopup()
    }.bind(this))

    this.store.subscribe('block/create/success', function(data){
        if(data.designer_id == this.designer_id) {
            if(!this.status) {
                if(this.stick_left) {
                    var body_width = $('body').width();

                    body_width = body_width - 340;
                    $('body').attr('style', 'width:' + body_width + 'px; margin-left:auto');
                }
            }
            this.block_id = data.block_id
            this.block_type = data.type
            this.initSetting()
            this.status = true
            this.update();
            this.initPopup()
        }
    }.bind(this))

    this.store.subscribe('block/setting/begin', function(data){
        if(data.designer_id == this.designer_id) {
            if(!this.status) {
                if(this.stick_left) {
                    var body_width = $('body').width();

                    body_width = body_width - 340;
                    $('body').attr('style', 'width:' + body_width + 'px; margin-left:auto');
                }
            }
            this.block_id = data.block_id
            this.block_type = data.type
            this.initSetting()
            this.status = true
            this.update()
            this.initPopup()
        }
    }.bind(this))

    this.initSetting = function() {
        if(this.block_type && this.block_id) {
            this.block_config = _.find(this.store.getState().config.blocks, function(block){
                return block.type == this.block_type
            }.bind(this))
            this.block_info = this.store.getState().blocks[this.designer_id][this.block_id]
            this.block_info.id = this.block_id
            if(this.block_info.parent == ''){
                this.classPopup = 'main-wrapper'
            } else if (this.block_config.setting.level_min == 1 && this.block_config.setting.child_blocks) {
                this.classPopup = 'main'
            } else if (this.block_config.setting.child_blocks) {
                this.classPopup = 'inner'
            } else {
                this.classPopup = 'child'
            }
            this.setting = this.store.getState().blocks[this.designer_id][this.block_id].setting
            this.marginTabs = {
                0: {
                    name: this.store.getLocal('designer.text_desktop'),
                    type: 'desktop',
                    is: 'vd-input-group',
                    opts: {block_id: this.block_id, designer_id: this.designer_id, 'name': 'design_margin_desktop'}
                },
                1: {
                    name: this.store.getLocal('designer.text_tablet'),
                    type: 'tablet',
                    is: 'vd-input-group',
                    opts: {block_id: this.block_id, designer_id: this.designer_id, 'name': 'design_margin_tablet'}
                },
                2: {
                    name: this.store.getLocal('designer.text_phone'),
                    type: 'phone',
                    is: 'vd-input-group',
                    opts: {block_id: this.block_id, designer_id: this.designer_id, 'name': 'design_margin_phone'}
                },
            }
            this.paddingTabs = {
                0: {
                    name: this.store.getLocal('designer.text_desktop'),
                    type: 'desktop',
                    is: 'vd-input-group',
                    opts: {block_id: this.block_id, designer_id: this.designer_id, 'name': 'design_padding_desktop'}
                },
                1: {
                    name: this.store.getLocal('designer.text_tablet'),
                    type: 'tablet',
                    is: 'vd-input-group',
                    opts: {block_id: this.block_id, designer_id: this.designer_id, 'name': 'design_padding_tablet'}
                },
                2: {
                    name: this.store.getLocal('designer.text_phone'),
                    type: 'phone',
                    is: 'vd-input-group',
                    opts: {block_id: this.block_id, designer_id: this.designer_id, 'name': 'design_padding_phone'}
                },
            }
        }
    }.bind(this)

    stickPopup(){
        if(!this.stick_left){
            var body_width = $('body').width();

            body_width = body_width - 340;
            $('body').attr('style', 'width:' + body_width + 'px; margin-left:auto');
            this.stick_left = true;
        } else {
            $('body').removeAttr('style');
            this.stick_left = false;
        }
        $(window).trigger('resize')
    }
    this.initPopup = function() {
        $('.vd-popup', this.root).resizable({
            start: function(){
                $('body').addClass('vd-resizable')
            },
            resize: function(event, ui) {
                if(this.stick_left) {
                    $('body').removeAttr('style');
                    this.stick_left = false
                }
                if(!$('.vd-popup', this.root).hasClass('drag')){
                    this.height = $('.vd-popup', this.root).height()
                    $('.vd-popup', this.root).css({'height': this.height });
                    $('.vd-popup', this.root).addClass('drag')
                }
                $('.vd-popup', this.root).css({ 'max-height': '' });
                this.update();
            }.bind(this),
            stop: function(event, ui) {
                this.width = ui.size.width
                this.height = ui.size.width
                $('body').removeClass('vd-resizable')
            }.bind(this)
        })
        $('.vd-popup', this.root).draggable({
            handle: '.popup-header',
            drag: function(event, ui) {
                if(this.stick_left) {
                    $('body').removeAttr('style');
                    this.stick_left = false
                }
                if (!ui.helper.hasClass('drag')) {
                    this.height = ui.helper.height()
                    $('.vd-popup', this.root).css({'height': this.height });
                    ui.helper.addClass('drag');
                }
            },
            stop: function(event, ui) {
                if (ui.position.top < 0) {
                    ui.helper.css({ 'top': '10px' });
                }
                var height = $(window).height();
                if ((ui.position.top + 100) > height) {
                    ui.helper.css({ 'top': (height - 100) + 'px' });
                }
                this.left = ui.position.left
                this.top = ui.position.top
            }.bind(this)
        });
        if (this.left != '' && this.top != '') {
            $('.vd-popup', this.root).addClass('drag');
            $('.vd-popup', this.root).css({ 'left': this.left, 'top': this.top });
        }
        if (this.width != '' && this.height != '') {
            $('.vd-popup', this.root).css({ 'width': this.width, 'height': this.height });
        }
        $('.vd-popup', this.root).css({ visibility: 'visible', opacity: 1 });
    }.bind(this)

    this.on('mount', function(){
        $(this.root).on('change', 'input.pixels-procent', function(){
            var value = $(this).val();
            var er = /^-?[0-9]+$/;
            var er2 = /^-?[0-9]+(px|%|rem)$/;

            if(er.test(value)){
                this.value = value+'px'
                var event = new Event('change');
                this.dispatchEvent(event);
            }
            else if(!er2.test(value)){
                $(this).val('');
                var event = new Event('change');
                this.dispatchEvent(event);
            }
        });
        $(this.root).on('change', 'input.pixels', function(){
            var value = $(this).val();
            var er = /^-?[0-9]+$/;
            var er2 = /^-?[0-9]+(px|rem)$/;

            if(er.test(value)){
                this.value = value+'px'
                var event = new Event('change');
                this.dispatchEvent(event);
            }
            else if(!er2.test(value)){
                $(this).val('');
                var event = new Event('change');
                this.dispatchEvent(event);
            }
        });
    })

    this.on('update', function(){
        this.initSetting()
    })

    change(e){
        if(e.target.type == 'checkbox'){
            var values = _.values(this.block_info.setting.global[e.target.name])
            if(e.target.checked) {
                values.push(e.target.value)
            } else {
                values = _.filter(values, function(name) {
                    return name != e.target.value
                })
            }
            this.block_info.setting.global[e.target.name] = _.extend({}, values)
        } else {
            this.block_info.setting.global[e.target.name] = e.target.value
        }
        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.designer_id, block_id: this.block_id, setting: this.block_info.setting})
    }

    responsiveMargin() {
        this.block_info.setting.global.design_margin_responsive = true

        this.block_info.setting.global.design_margin_desktop_top = this.block_info.setting.global.design_margin_top
        this.block_info.setting.global.design_margin_desktop_right = this.block_info.setting.global.design_margin_right
        this.block_info.setting.global.design_margin_desktop_bottom = this.block_info.setting.global.design_margin_bottom
        this.block_info.setting.global.design_margin_desktop_left = this.block_info.setting.global.design_margin_left

        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.designer_id, block_id: this.block_id, setting: this.block_info.setting})
    }

    cancelMargin() {
        this.block_info.setting.global.design_margin_responsive = false

        this.block_info.setting.global.design_margin_top = this.block_info.setting.global.design_margin_desktop_top
        this.block_info.setting.global.design_margin_right = this.block_info.setting.global.design_margin_desktop_right
        this.block_info.setting.global.design_margin_bottom = this.block_info.setting.global.design_margin_desktop_bottom
        this.block_info.setting.global.design_margin_left = this.block_info.setting.global.design_margin_desktop_left

        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.designer_id, block_id: this.block_id, setting: this.block_info.setting})
    }

    responsivePadding() {
        this.block_info.setting.global.design_padding_responsive = true

        this.block_info.setting.global.design_padding_desktop_top = this.block_info.setting.global.design_padding_top
        this.block_info.setting.global.design_padding_desktop_right = this.block_info.setting.global.design_padding_right
        this.block_info.setting.global.design_padding_desktop_bottom = this.block_info.setting.global.design_padding_bottom
        this.block_info.setting.global.design_padding_desktop_left = this.block_info.setting.global.design_padding_left

        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.designer_id, block_id: this.block_id, setting: this.block_info.setting})
    }

    cancelPadding() {
        this.block_info.setting.global.design_padding_responsive = false

        this.block_info.setting.global.design_padding_top = this.block_info.setting.global.design_padding_desktop_top
        this.block_info.setting.global.design_padding_right = this.block_info.setting.global.design_padding_desktop_right
        this.block_info.setting.global.design_padding_bottom = this.block_info.setting.global.design_padding_desktop_bottom
        this.block_info.setting.global.design_padding_left = this.block_info.setting.global.design_padding_desktop_left

        this.store.dispatch('block/setting/fastUpdate', {designer_id: this.designer_id, block_id: this.block_id, setting: this.block_info.setting})
    }

    cancel() {
        this.store.dispatch('history/undo', {block_id: this.block_id, designer_id: this.designer_id, fast: false})
        this.status = false
        this.block_id = ''
        this.block_type = ''
        $('body').removeAttr('style');
        $(window).trigger('resize')
    }

    close() {
        this.status = false
        this.block_id = ''
        this.block_type = ''
       
        $('body').removeAttr('style');
        $(window).trigger('resize')
    }
</script>
</vd-popup-setting-block>
