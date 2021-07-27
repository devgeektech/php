var countdown_base = {
    setting:{
        labels:["weeks", "days", "totalDays", "hours", "minutes", "seconds"],
        format:'%w:%d:%D:%H:%M:%S'
    },
    settings:{},
    //Инициализация начальных значений
    init: function(setting){
        this.setting = $.extend({}, this.setting, setting); 
        this.settings[setting.form.attr('id')] =  $.extend({}, this.setting, setting);
    },
    //
    step:function(countdown_id, e){  
        var countdown_setting =  this.settings[countdown_id];
        var newDate = e.strftime(this.settings[countdown_id].format);
        
        newDate = this.prepareDate(newDate);
        
        for (var key in newDate){
            var selector = '[data-type=%s]'.replace(/%s/, key),
            $node = this.settings[countdown_id].form.find(selector).text(newDate[key]);
        }
    },
    //Преобразовует дату в объект
    prepareDate:function(date){
        var str = date.match(/([0-9]{2})/gi);
        var result = {};
        for(var key in this.setting.labels){
            result[this.setting.labels[key]] = str[key];
        }
        return result;
    }
}