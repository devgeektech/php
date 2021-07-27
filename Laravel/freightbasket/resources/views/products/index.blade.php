@extends('layouts.usertemplate')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Products</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    @if(count($products) > 0)
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Image</th>
            <th>Name</th>
            <th>Details</th>
            <th>HS Code</th>
            <th>Min Order</th>
            <th>Max Order</th>
            <th>Contact With Seller</th>
            <th>Action</th>
        </tr>
            @foreach ($products as $product)
            <tr>
                <td>{{ ++$i }}</td>
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
                <td><img class="img-fluid" src="{{ asset('/uploads/products/'.$json_product_imge) }}"></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->detail }}</td>
                <td>{{ $product->hscode }}</td>
                <td>{{ $product->min }}</td>
                <td>{{ $product->max }}</td>
                <td>{{ $product->contactwithseller }}</td>
                <td>
                    <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                        <a class="action-icons" href="{{ route('products.show',$product->id) }}"><i class="fa fa-eye  "></i></a>
                        <a class="action-icons" href="{{ route('products.edit',$product->id) }}"><i class="fa fa-edit  "></i></a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-icons"><i class="fa fa-trash-o  "></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
    </table>
  
    {!! $products->links() !!}
    @else
        <h3>No Product Found</h3>
    @endif
      
@endsection