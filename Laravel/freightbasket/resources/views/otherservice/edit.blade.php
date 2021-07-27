@extends('layouts.usertemplate')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Service</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('companyprofile') }}"> Back</a>
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
  
    <form action="{{ route('otherservice.update',$otherservice->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{ Form::hidden('service_type', $otherservice->service_type) }}
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Service Name:</strong>
                    <input type="textbox" col="4" name="name" class="form-control" value="{{ $otherservice->name }}" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description</strong>
                    <textarea col="4" name="description" class="form-control" required>{{ $otherservice->name }}</textarea>
                </div>
            </div>
            @if($otherservice->images != "null")
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>Images:</strong>
                <div class="form-group container row">
                    @php
                        $json_timeline_img = json_decode($otherservice->images);
                    @endphp
                    @foreach($json_timeline_img as $json_timeline_imge)
                    <div class="edit_otherservice_image col-lg-2">
                        <img class="img-fluid" src="{{ asset('/uploads/otherservice/'.$json_timeline_imge) }}">
                        <button data-id="{{$json_timeline_imge}}" data-val="{{$otherservice->id}}" data-valnew="otherservice" class="remove-img">Remove Image</button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Services Images</strong>
                    <input type="file" name="images[]" id="image" class="form-control" multiple>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
@endsection