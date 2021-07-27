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