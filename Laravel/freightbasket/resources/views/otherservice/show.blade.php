@extends('layouts.usertemplate')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Service</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('companyprofile') }}"> Back</a>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong>
                @php
                    $json_product_img = json_decode($otherservice->images);
                @endphp
                @if(isset($json_product_img) && !empty($json_product_img))
                    @php
                        $json_product_imge = $json_product_img[0];
                    @endphp
                @else
                    @php
                        $json_product_imge = 'defaultProduct.jpg';
                    @endphp
                @endif
                <img class="img-fluid" src="{{ asset('/uploads/otherservice/'.$json_product_imge) }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $otherservice->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Description:</strong>
                {{ $otherservice->description }}
            </div>
        </div>
    </div>
@endsection