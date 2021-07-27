@extends('layouts.usertemplate')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Post</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('Udashboard') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('timeline.update',$timeline->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Post Name:</strong>
                    <input type="textbox" col="4" name="message" class="form-control" value="{{ $timeline->message }}" required>
                </div>
            </div>
            @if(!empty($timeline->images))
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>Images:</strong>
                <div class="form-group container row">
                    @php
                        $json_timeline_img = json_decode($timeline->images);
                    @endphp
                    @foreach($json_timeline_img as $json_timeline_imge)
                    <div class="edit_timeline_image col-lg-2">
                        <img class="img-fluid" src="{{ asset('/uploads/timeline/'.$json_timeline_imge) }}">
                        <button data-id="{{$json_timeline_imge}}" data-val="{{$timeline->id}}" class="remove-img">Remove Image</button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Timeline Images</strong>
                    <input type="file" name="images[]" id="image" class="form-control" multiple>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
@endsection