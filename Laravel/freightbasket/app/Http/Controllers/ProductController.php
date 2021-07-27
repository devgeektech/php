<?php

namespace App\Http\Controllers;

use File;
use Auth;
use Response;
use App\Product;
use App\Productimage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
  
class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Product::latest()->paginate(5);
        
        $products = Product::where('user_id', Auth::id())->paginate(5);
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $slug = Str::slug($request->name);
        $request->request->add(['slug' => $slug]);
        $this->validate($request, [
                    'slug' => 'unique:products,slug',
                    'product_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ],
            [
              'slug.unique'=> 'Name of the product already exist.', // custom message
            ]
        );

        // dd($request->product_image);

    
        if($request->hasfile('product_image'))
         {
            foreach($request->file('product_image') as $file)
            {
                $name = uniqid().time().'.'.$file->extension();
                $file->move(public_path().'/uploads/products/', $name);  
                $data[] = $name;  
            }
         }

		$request->merge([
		    'product_image' => '',
		]);
    	$product = Product::create($request->all());
		$product->product_image = json_encode($data);
		$product->save();

        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$product = Product::where('user_id', Auth::id())->find($id);
        
        if(empty($product)){
        	return redirect()->back();
        }else{
        	return view('products.show',compact('product'));
        }
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$product = Product::where('user_id', Auth::id())->find($id);    	
        if(empty($product)){
        	return redirect()->back();
        }else{
        	return view('products.edit',compact('product'));
        }
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
    	$data = json_decode($product->product_image);
		$request->merge([
		    'product_image' => '',
		]);
        $product->update($request->all());
  		if($request->hasfile('product_image'))
        {
            foreach($request->file('product_image') as $file)
            {
                $name = uniqid().time().'.'.$file->extension();
                $file->move(public_path().'/uploads/products/', $name);  
                $data[] = $name;  
            }
        }

		$product->product_image = json_encode($data);
		$product->save();
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$product = Product::where('user_id', Auth::id())->find($id);  
    	$product_images = json_decode($product->product_image);
    	if(!empty($product_images)){
    		foreach ($product_images as $product_image) {
    			# code...
	    		$image_path = public_path()."/uploads/products/".$product_image;
				if(File::exists($image_path)) {
				    File::delete($image_path);
				}
    		}
    	}
    	$product->delete();
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }

    public function removeimage(Request $request)
    {
    	$product = Product::where('user_id', Auth::id())->find($request->product_id);  
    	$product_images = json_decode($product->product_image);
    	if(!empty($product_images)){
    		$new_result = array_diff($product_images,[$request->image_name]);
    		$new_result = array_values(array_filter($new_result));

    		$image_path = public_path()."/uploads/products/".$request->image_name;
			if(File::exists($image_path)) {
			    File::delete($image_path);
			}
    	}
    	$product->product_image = json_encode($new_result);
    	$product->save();
        return Response::json(array('success'=>true));
    }
}