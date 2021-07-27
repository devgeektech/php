@extends('layouts.usertemplate')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Image:</strong>
                @php
                    $json_product_img = json_decode($product->product_image);
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
                <img class="img-fluid" src="{{ asset('/uploads/products/'.$json_product_imge) }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $product->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Details:</strong>
                {{ $product->detail }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>HS Code:</strong>
                {{ $product->hscode }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Min Order:</strong>
                {{ $product->min }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Max Order:</strong>
                {{ $product->max }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Contact With Seller:</strong>
                {{ $product->contactwithseller }}
            </div>
        </div>
    </div>
@endsection