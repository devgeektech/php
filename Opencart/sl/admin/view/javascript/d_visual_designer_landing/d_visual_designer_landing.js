var d_visual_designer_landing = {
    //Настройки
    setting:{
        //url адрес
        url: 'index.php?route=extension/module/d_visual_designer_landing',
        //форма
        form:{},
        //Текущая активная вариация
        variation_id: '',
        //Токен
        token: getURLVar('token'),
        //Page ID
        page_id:'',
        //Статус инициализации
        initial:true
    },
    //Шаблоны
    template:{
        //шаблон элемента меню
        element_menu:'',
        //Форма вариации
        variation_form:''
    },
    //данные
    data:{},
    //Инициализация начальных значений
    init: function(setting){
        that = this;
        this.setting = $.extend({}, this.setting, setting);
    },
    //Инициализация шаблонов
    initTemplate:function(template){
        this.template = $.extend({}, this.template, template);
    },
    //Инициализация данных
    initData:function(data){
        this.data = $.extend({}, this.data, data);
    },
    //Инициализация summernote
    intiSummernote: function(selector){
        this.setting.form.find(selector).summernote({
            height: '300px'
        })
    },
    //Компиляция шаблона
    templateСompile: function(template,data){
        var source = template;
        Handlebars.registerHelper('if_eq', function(a, b, opts) {
            if(a == b) // Or === depending on your needs
                return opts.fn(this);
            else
                return opts.inverse(this);
        });
        var template = Handlebars.compile(source);
        var html = template(data);
        return html;
    },
    //Прорисовка вариации
    renderVariation:function(variation_id, first=false){
        if(variation_id != null){
            this.saveCurrentVariation();
            if(this.data[variation_id] != undefined){
                var context = this.data[variation_id];
                var html = this.templateСompile(this.template.variation_form, context);
                this.setting.form.find('#variation-form').html(html);
                this.setting.form.find('#variation-form [id=language-variation] a').tab('show');
                this.intiSummernote('#variation-form [name$=\'[description]\']');
                this.setting.variation_id = variation_id;
                if(!this.setting.initial){
                    var setting = {
                        form: $('.d_visual_designer:first').parents('form'),
                    };
                    d_visual_designer = d_visual_designer||{};
                    d_visual_designer.init(setting);
                }
            }
        }
    },
    //Синхронизация изменений с сервером
    updateVariation:function(variation_id){
        var that = this;
        var send_data = {
            'variation_id' : variation_id,
            'variation_data' : this.data[variation_id]
        }
        $.ajax({
            url: this.setting.url+'/editVariation&token='+this.setting.token,
            type: 'POST',
            dataType: 'JSON',
            data: send_data,
            success: function(json){
                if(json['success']){
                    console.log('d_visual_designer_landing:update_variation');
                }
            }
        });
    },
    //Добавление новой вариации
    addVariation:function(){
        
        this.saveCurrentVariation();
        
        var variation_id = this.getRandomString();
        
        this.data[variation_id] = {
            description:'',
            character:String.fromCharCode(this.setting.char_code++),
            new:true
        };
        
        var html = this.templateСompile(this.template.element_menu, {key: variation_id, character:this.data[variation_id]['character']});

        this.setting.form.find('#variation >  li').removeClass('active');
        this.setting.form.find('#variation > .add-variation').before(html);
        
        var context = this.data[variation_id];
        var html = this.templateСompile(this.template.variation_form, context);
        this.setting.form.find('#variation-form').html(html);
        this.setting.form.find('#variation-form [id=language-variation] a').tab('show');
        this.intiSummernote('#variation-form [name$=\'[description]\']');
        this.setting.variation_id = variation_id;
        
        this.saveCurrentVariation();
        var setting = {
            form: $('.d_visual_designer:first').parents('form'),
        };
        d_visual_designer = d_visual_designer||{};
        d_visual_designer.init(setting);
    },
    //Копирование вариации
    copyVariation:function(){
        
        this.saveCurrentVariation();
        
        var variation_id = this.getRandomString();
        
        this.data[variation_id] = {
            description: this.data[this.setting.variation_id]['description'],
            character:String.fromCharCode(this.setting.char_code++),
            new:true
        };
        
        var html = this.templateСompile(this.template.element_menu, {key: variation_id, character:this.data[variation_id]['character']});
        
        this.setting.form.find('#variation >  li').removeClass('active');
        this.setting.form.find('#variation > .add-variation').before(html);
        
        var context = this.data[variation_id];
        var html = this.templateСompile(this.template.variation_form, context);
        this.setting.form.find('#variation-form').html(html);
        this.setting.form.find('#variation-form [id=language-variation] a').tab('show');
        this.intiSummernote('#variation-form [name$=\'[description]\']');
        this.setting.variation_id = variation_id;
        
        this.saveCurrentVariation();
        var setting = {
            form: $('.d_visual_designer:first').parents('form'),
        };
        d_visual_designer = d_visual_designer||{};
        d_visual_designer.init(setting);
    },

    //Удаление вариация
    deleteVariation:function(){
        
        var that = this;
        
        var variation_id = this.setting.variation_id;

        if(this.data[variation_id] != undefined){
            $.ajax({
                url: that.setting.url+'/deleteVariation&token='+that.setting.token,
                type: 'POST',
                dataType: 'JSON',
                data: {variation_id: variation_id},
                success: function(json){
                    if(json['success']){
                        console.log('d_visual_designer_landing:delete_variation');
                        that.setting.form.find('#variation-form').empty();
                        that.setting.form.find('#variation > li > a[data-id='+variation_id+']').closest('li').remove();
                        delete that.data[variation_id];
                        that.setting.form.find('#variation > li:not(.add-variation):first > a').trigger('click');
                    }
                }
            });
        }
    },
    //Сохранение текущей вариации
    saveCurrentVariation:function(){
        if(this.setting.variation_id != ''){
            var data = this.setting.form.find('#variation-form').find("input, textarea, select").serializeJSON();
            
            this.data[this.setting.variation_id] = $.extend({}, this.data[this.setting.variation_id], data);
            
            this.updateVariation(this.setting.variation_id);
        }
    },
    //Сохранение формы
    saveForm:function(){
        var that = this;
        
        this.saveCurrentVariation();
        
        var send_data = that.setting.form.find('input, select, textarea').serializeJSON();
        send_data['variations'] = this.data;
        
        delete send_data['description'];
        
        $.ajax({
            url: that.setting.form.attr('action'),
            type: 'POST',
            dataType: 'JSON',
            data: send_data,
            success:function(json){
                if(json['success']){
                    // location.href = json['redirect'];
                }
                
                if(json['error']){
                    that.renderErrors(json['errors']);
                }
            }
        });
        
    },
    //Прорисовка ошибок
    renderErrors:function(errors){
        for (var key in errors){
            this.setting.form.find('[name='+key+']').after('<div class="alert alert-danger">Error</div>');
            this.setting.form.find('[name='+key+']').closest('.form-group').addClass('has-error');
        }
    },
    //Возвращает рандомную строку
    getRandomString:function(){
        return Math.random().toString(36).substring(2,9);
    }
}