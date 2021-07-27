<vd-block-progress_bar>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <div class="progress" each={value, key in getState().setting.global.values}>
        <div class="progress-bar progress-bar-danger {getState().setting.global.stripes?'progress-bar-striped':''} {getState().setting.global.animate?'active':''}" role="progressbar" aria-valuenow="{value.value}" aria-valuemin="0" aria-valuemax="100" style="width: {value.value}%; background-color:{value.color}">
            {value.label} {value.value}{getState().setting.global.units} 
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-progress_bar>