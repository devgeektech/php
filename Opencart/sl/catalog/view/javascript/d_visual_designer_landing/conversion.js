var conversion_base = {
    //Лог событий
    log:{},
    //Ининциализация
    init:function(description_id){
        if(this.log[description_id] == undefined){
            this.log[description_id] = {};
        }
    },
    //Добавление конверсии
    addConversion:function(description_id, type){

        if(!_.isUndefined(this.log[description_id]) && _.isUndefined(this.log[description_id][type])){
            this.log[description_id][type] = true;
            var send_data = {
                'description_id': description_id,
                'type': type
            };
            $.ajax({
                url:'index.php?route=extension/d_visual_designer_module/conversion/addConversion',
                type:'post',
                dataType:'json',
                data: send_data,
                success: function(json){
                    console.log(json);
                }
            });
        }
    },
    //Добавление конверсии продажи
    addConversionBuy:function(description_id, product_id){

        if(!_.isUndefined(this.log[description_id]) && _.isUndefined(this.log[description_id]['buy'])){
            this.log[description_id]['buy'] = true;
            var send_data = {
                'description_id': description_id,
                'product_id': product_id
            };
            $.ajax({
                url:'index.php?route=extension/d_visual_designer_module/conversion/addConversionBuy',
                dataType:'json',
                type:'post',
                data:send_data
            });
        }
    },
}