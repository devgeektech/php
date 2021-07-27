<vd-block-separater>
    <style>
        .vd-separater-left{
            margin-right:auto;
        }
        .vd-separater-center{
            margin-left:auto;
            margin-right:auto;
        }
        .vd-separater-right{
            margin-left:auto;
        }
    </style>
    <div style="width:100%;">
        <div class="vd-separater-{getState().setting.global.align}" style="width:{getState().setting.global.width};">
            <hr style="border-top: {getState().setting.global.border_width} {getState().setting.global.style} {getState().setting.global.color};"/>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-separater>