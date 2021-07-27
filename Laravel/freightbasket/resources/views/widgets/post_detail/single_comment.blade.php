<div class="panel-default post-comment" id="post-comment-{{ $comment->id }}">
    <div class="panel-body">
        <div class="pull-left">
            <a href="{{ url('/'.$comment->user->username) }}">
                @if($comment->user->avatar == "users/default.png" )
                <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="media-object rounded-circle comment-profile-photo">
                @else
                <img src="{{ asset( 'uploads/profiles/'.$comment->user->avatar ) }}" alt="" class="media-object rounded-circle comment-profile-photo">
                @endif
            </a>
        </div>
        <div class="pull-left comment-info">
            <a href="{{ url('/'.$comment->user->username) }}" class="name">{{ $comment->user->name }}</a>
            <a href="{{ url('/'.$comment->user->username) }}" class="username">{{ '@'.$comment->user->username }}</a>
            <span class="date"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $comment->created_at->diffForHumans() }}</span>
            @if($post->user_id == Auth::id() || $comment->comment_user_id == Auth::id())
                <a href="javascript:;" class="remove pull-right" onclick="removeComment({{ $comment->id }}, {{ $post->id }})"><i class="fa fa-times"></i></a>
            @endif
            <div class="clearfix"></div>
        </div>
        <br />
        <p>
            {{ $comment->comment }}
        </p>
    </div>
</div>

<div class="clearfix"></div>

<hr />