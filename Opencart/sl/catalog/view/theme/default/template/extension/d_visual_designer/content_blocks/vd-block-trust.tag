<vd-block-trust>
    <div class="h2" if={getState().setting.global.title}>{getState().setting.global.title}</div>
    <div>
        <a each={image in getState().setting.user.images} class="image-popup" target="_blank" href="{image.link}" style="text-decoration: none;">
            <img src="{image.thumb}"/>
        </a>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-trust>