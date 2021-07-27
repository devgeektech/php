@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->

<div class="row">
  <div class="col-sm-12">
    <div class="page-title-box">
      <div class="float-right">
      </div>
      <h4 class="page-title">Member Profile</h4>
    </div>
    @if (Session::has('success'))
    <p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
    @endif
    @if (Session::has('error'))
    <p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
    @endif
    
  </div>
</div>
@if( !empty($simpleuser) )
<div class="row">
  <div class="col-lg-4 custom-sidebar-myprofile">
    
    <div class="card">
      <div class="card-body">
          
          <div class="profile-pic">
            @if($simpleuser->avatar == "users/default.png" )
            <img src="{{ asset('uploads/profiles/default-user-photo.jpg') }}" alt="" class="img-fluid mx-auto d-block">
            @else
            <img src="{{ asset('uploads/profiles/'.$user->avatar) }}" alt="" class="img-fluid mx-auto d-block">
            @endif            
          </div>
        
        <h5>About</h5>
        <h4 class="mt-2 header-title"><span class="mr-2"><i class="fa fa-user"></i></span>{{$simpleuser->name }}</h4>
        <p class="mb-2"><i class="fa fa-envelope"></i><span class="text-mute ml-2">{{$simpleuser->email }}</span></p>
        <p><i class="fa fa-phone"></i><span class="text-mute ml-2">{{$simpleuser->phone }}</span></p>

        
        <div class="col-lg-12 send_friend_request">
            @if($simpleuser->friends)
              @if(!empty($simpleuser->friends))
                @foreach($simpleuser->friends as $friend)
                  @if($friend->pivot->second_user)                    
                    <a href="javascript:;" class="send" onclick="send_friend_request({{ $simpleuser->id }});"><span>Remove friend</span></a>
                  @endif
                @endforeach
              @endif
            @endif

          @if($user->friend_requests())
            @if(!empty($user->friend_requests))
                @foreach($user->friend_requests as $user_friend)
                    @if($user_friend->second_user == $simpleuser->id )
                        @if($user_friend->status == "pending")
                            <a href="javascript:;" class="send" onclick="accept_friend_request({{ $simpleuser->id }});"><span>Accept friend</span></a>
                            <a href="javascript:;" class="send" onclick="send_friend_request({{ $simpleuser->id }});"><span>Cancel Request</span></a>

                        @endif
                    @endif
                @endforeach
            @endif
          @endif
         
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <a class="" href="{{ route('user.coprofile', $simpleuser->id) }}">Go to Company Profile</a>
      </div>
    </div>

  </div>

  <div class="col-md-8">

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <nav class="nav nav-pills nav-justified">
              <a class="nav-item nav-link bg-secondary text-white active" href="#home" data-toggle="tab">Member Detail</a>            
            </nav>
            <div class="tab-content" id="myTabContent">
              <!-- first tab -->
              <div class="tab-pane fade show active" id="home">
                <div class="row mt-4">
                    <div class="col-md-6">
                     <p> <i class="fa fa-user"></i> <b>Name</b></p>
                      {{$simpleuser->name}}
                    </div>
                    <div class="col-md-6">
                     <p> <i class="fa fa-envelope"></i> <b>Email</b></p>
                      {{$simpleuser->email}}
                  </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                     <p> <i class="fa fa-phone"></i> <b>Phone NUmber</b></p>
                      {{$simpleuser->phone}}
                    </div>
                    <div class="col-md-6">
                     <p> <i class="fa fa-map-marker"></i> <b>Address</b></p>
                      {{$simpleuser->address}}
                     </div>                  
                </div>
                 <div class="row mt-4">
                    <div class="col-md-6">
                     <p> <i class="fa fa-globe"></i> <b>Country</b></p>
                      {{$simpleuser->country}}
                    </div>
                    <div class="col-md-6">
                     <p> <i class="fa fa-info-circle"></i> <b>About</b></p>
                      {{$simpleuser->about}}
                     </div>                  
                </div>
              </div>
              <!-- end of first tab -->                          
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
          <h5>Timeline</h5>
          @if(count($timeline) > 0)
            @php($i = 0)
            @php($post_max_id = 0)
            @php($post_min_id = 0)
            @foreach($timeline as $post)
                @if($i == 0)
                    @php($post_max_id = $post->id)
                @endif
                @php($post_min_id = $post->id)

                @include('widgets.post_detail.single_post')

                @php($i++)
            @endforeach
          @else 
            Not Found any timeline yet..!!!
          @endif
      </div>
    </div>
  </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <h3>Not Found any record</h3>
    </div>
</div>
@endif

@endsection



