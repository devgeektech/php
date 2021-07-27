@php
$data_exist = true;
@endphp
<div class="row col-md-12 send_friend_request">
@if($user->friend_requests_first_user())
    @if(!empty($user->friend_requests_first_user))
        @foreach($user->friend_requests_first_user as $user_pending_friend)
            @if($user_pending_friend->second_user == $post->user->id)
            	@php
            	$data_exist = false;
            	@endphp
                @if($user_pending_friend->status == "pending")
                    <a href="javascript:;" class="send" onclick="send_friend_request({{ $post->user->id }});"><span>Cancel Request</span></a>
                @elseif($user_pending_friend->status == "confirmed")
                    <a href="javascript:;" class="send" onclick="send_friend_request({{ $post->user->id }});"><span>Remove friend</span></a>
			    @else
					<a href="javascript:;" class="send" onclick="send_friend_request({{ $post->user->id }});"><span>Send friend request</span></a>
                @endif
            
            @endif
        @endforeach
		@if(isset($data_exist) && $data_exist == true)
		<a href="javascript:;" class="send" onclick="send_friend_request({{ $post->user->id }});"><span>Send friend request</span></a>
		@endif
	@else
		<a href="javascript:;" class="send" onclick="send_friend_request({{ $post->user->id }});"><span>Send friend request</span></a>
    @endif
@endif
</div>