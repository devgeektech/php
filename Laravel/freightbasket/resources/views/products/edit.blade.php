@extends('layouts.usertemplate')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
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
  
    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Detail:</strong>
                    <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $product->detail }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>HS Code:</strong>
                    <input type="text" name="hscode" value="{{ $product->hscode }}" class="form-control" placeholder="HS Code" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Min Order:</strong>
                    <input type="number" name="min" value="{{ $product->min }}" class="form-control" placeholder="Min Order" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Max Order:</strong>
                    <input type="number" name="max" value="{{ $product->max }}" class="form-control" placeholder="Max Order" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Contact With Seller:</strong>
                    <select name="contactwithseller" id="contactwithseller" class="form-control" required> <!--A is used for Arriavl-->
                        <option value="chat" {{ $product->contactwithseller == "chat" ? 'selected' : '' }} >Chat</option>  
                        <option value="mail" {{ $product->contactwithseller == "mail" ? 'selected' : '' }} >Mail</option>  
                    </select>
                </div>
            </div>
            @if(!empty($product->product_image))
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>Images:</strong>
                <div class="form-group container row">
                    @php
                        $json_product_img = json_decode($product->product_image);
                    @endphp
                    @foreach($json_product_img as $json_product_imge)
                    <div class="edit_product_image col-lg-2">
                        <img class="img-fluid" src="{{ asset('/uploads/products/'.$json_product_imge) }}">
                        <button data-id="{{$json_product_imge}}" data-val="{{$product->id}}" class="remove-img">Remove Image</button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Product Images</strong>
                    <input type="file" name="product_image[]" id="image" class="form-control" multiple>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
@endsection