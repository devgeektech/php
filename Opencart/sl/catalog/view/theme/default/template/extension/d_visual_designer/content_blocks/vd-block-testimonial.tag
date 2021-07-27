<vd-block-testimonial>
    <div class="vd-testimonial">
        <div class="vd-testimonial-image">
            <img src="{getState().setting.user.thumb}"/>
        </div>
        <div class="vd-testimonial-name h3">{getState().setting.global.name}</div>
        <div class="vd-testimonial-comment"><raw html={getState().setting.user.comment}/></div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-testimonial>