<vd-block-star_rating>
    <style type="text/css" media="screen">
        .vd-rating{
            text-align: center;
            width: 100%;
        }
        .vd-rating > .vd-rating-stars {
            overflow: auto;
            display: flex;
            justify-content: center;
        }
        .vd-rating > .vd-rating-stars > i{
            float:left;
        }
        .vd-rating > .title{
            display: flex;
            justify-content: center;
        }
        .vd-rating .fas.fa-star{
            color: #FC0;
        }
        .vd-rating .fa-1x{
            font-size: 16px;
        }
        .vd-rating .vd-rating-stars-stack{
            position: relative;
            margin: 2px;
        }
        .vd-rating .far.fa-star {
            color: #999;
        }
        .vd-rating .fas.fa-star + .far.fa-star {
            position: absolute;
            color: #E69500;
            left: 0;
            top: 0;
            width: 100%;
            text-align: center;
        }
    </style>
    <div class="vd-rating">
        <div class="vd-rating-stars">
            <span class="vd-rating-stars-stack" each={value in _.range(1, (Number(getState().setting.global.rating)+1))}>
                <i class="fas fa-star fa-{getState().setting.global.star_size}x" aria-hidden="true"></i>
                <i class="far fa-star fa-{getState().setting.global.star_size}x" aria-hidden="true"></i>
            </span>
            <span class="vd-rating-stars-stack" each={value in _.range(Number(getState().setting.global.rating), 5)} if={getState().setting.global.rating < 5}>
                <i class="far fa-star fa-{getState().setting.global.star_size}x" aria-hidden="true"></i>
            </span>
        </div>
        <p class="title">{getState().setting.global.title}</p>
    </div>
    <script>
        this.mixin(new vd_block(this))
    </script>
</vd-block-star_rating>