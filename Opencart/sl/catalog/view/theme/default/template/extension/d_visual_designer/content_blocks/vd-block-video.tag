<vd-block-video>
    <div style="width:{getState().setting.global.width}">
        <div class="wrapper-video vd-video-aspect-ration-{getState().setting.global.ratio}">
            <iframe src="{getLink()}" frameborder="0" allowfullscreen width="638" height="358" class="video-player"></iframe>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))

        this.getLink = function(){
            var link = ''
            var setting = this.getState().setting
            if(setting.global.link.indexOf('youtube') != -1){
                var matches = setting.global.link.match(/(v=)([a-zA-Z0-9]+)/)
                if(matches != null){
                    var youtube_id = matches[2]
                    link = setting.global.link.replace('watch?v=', 'embed/') + "?playlist="+youtube_id
                }
            } else if (setting.global.link.indexOf('vimeo') != -1){
                link = setting.global.link.replace('vimeo.com', 'player.vimeo.com/video')
            }
             link = link.replace('https:', '').replace('http:', '')
            return window.location.protocol + link
        }.bind(this)
    </script>
</vd-block-video>