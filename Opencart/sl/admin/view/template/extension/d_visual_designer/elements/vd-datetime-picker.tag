<vd-datetime-picker>
    <div class="input-group date">
        <input type="text" name="datetime" value="{opts.riotValue}" placeholder="{opts.placeholder}" class="form-control" data-date-format="{opts.format? opts.format:'YYYY-MM-DD HH:mm'}" onChange={change}/>
        <span class="input-group-btn">
            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span>
    </div>
    <script>
        this.on('mount', function(){
            var options = {pickDate: true, time: true}
            if(!_.isUndefined(this.opts.date)){
                options.pickDate = this.opts.date
            }
            if(!_.isUndefined(this.opts.time)){
                options.pickTime = this.opts.time
            }
             $('.date', this.root).datetimepicker(options).on('dp.change', function(e){
                this.opts.evchange({target: {
                    name: this.opts.name,
                    value: e.date.format(this.opts.format)
                }})
             }.bind(this));
        })
    </script>
</vd-datetime-picker>