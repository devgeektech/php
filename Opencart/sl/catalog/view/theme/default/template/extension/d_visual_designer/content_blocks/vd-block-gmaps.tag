<vd-block-gmaps>
    <div style="{getState().setting.global.height?'height:'+getState().setting.global.height+';':''}">
    <raw html={getState().setting.global.link}/>
    <style>
        raw > div {
            height: 100%;
        }
    </style>
</div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-gmaps>