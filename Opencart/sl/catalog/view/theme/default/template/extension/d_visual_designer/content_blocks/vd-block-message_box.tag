<vd-block-message_box>
    <style>
        .vd-message{
            width: 100%;
            min-height: 45px;
            border: 1px solid transparent;
            display: block;
            overflow: hidden;
            padding: 1em 1em 1em 4em;
            position: relative;
            font-size: 1em;
            box-sizing: border-box;
        }
        .vd-message > .icon{
            bottom: 0;
            font-size: 1em;
            font-style: normal;
            font-weight: 400;
            left: 0;
            position: absolute;
            top: 0;
            width: 4em;
            color:white;
        }
        .vd-message > .icon > span{
            font-style: normal;
            left: 30%;
            position: absolute;
            top: 30%;
            font-size: 1.7em;
            line-height: 1;
        }
        .vd-message > .text{
            float:left;
            color:white;
        }
        .vd-message-share-round{
            border-radius: 4em;
        }
        .vd-message-share-rounded{
            border-radius: 4px;
        }
    </style>
    <div class="vd-message vd-message-style-{getState().setting.global.style} vd-message-share-{getState().setting.global.share}" style="background:{getState().setting.global.color};">
        <div class="icon">
            <span class="{getState().setting.global.icon}" style="color:{getState().setting.global.border_color}"></span>
        </div>
        <div class="text" style="color:{getState().setting.global.border_color}">
            <raw html={getState().setting.user.text}/>
        </div>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-message_box>