<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Otherservice;
use App\PostComment;
use App\PostLike;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Response;
use Session;
use View;
use File;

class OtherserviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return view('otherservice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
                'name' => 'required|unique:otherservices,name',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ],
            [
              'name.unique'=> 'Name of the Service already exist.', // custom message
            ]
        );

        $request->merge([
            'images' => json_encode(null),
            'user_id' => Auth::user()->id
        ]);
        // dd($request);
        $otherservice = Otherservice::create($request->all());
        if($request->hasfile('images'))
         {
            foreach($request->file('images') as $file)
            {
                $name = uniqid().time().'.'.$file->extension();
                $file->move(public_path().'/uploads/otherservice/', $name);  
                $data[] = $name;  
            }
            $otherservice->images = json_encode($data);
         }

        $otherservice->save();

        return redirect()->route('companyprofile')
                        ->with('success','Otherservice created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Otherservice  $otherservice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $otherservice = Otherservice::where('user_id', Auth::id())->find($id);        
        if(empty($otherservice)){
            return redirect()->back();
        }else{
            return view('otherservice.show',compact('otherservice'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Otherservice  $otherservice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $otherservice = Otherservice::where('user_id', Auth::id())->find($id);        
        if(empty($otherservice)){
            return redirect()->back();
        }else{
            return view('otherservice.edit',compact('otherservice'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Otherservice  $otherservice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $otherservice = Otherservice::where('user_id', Auth::id())->find($id);        
        $data = json_decode($otherservice->images);
        $request->merge([
            'images' => '',
        ]);
        $otherservice->update($request->all());
        if($request->hasfile('images'))
        {
            foreach($request->file('images') as $file)
            {
                $name = uniqid().time().'.'.$file->extension();
                $file->move(public_path().'/uploads/otherservice/', $name);  
                $data[] = $name;  
            }
        }

        $otherservice->images = json_encode($data);
        $otherservice->save();
        return redirect()->route('companyprofile')
                        ->with('success','Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Otherservice  $otherservice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $otherservice = Otherservice::find($id);

        if($otherservice->images != null){
            $timeline_images = json_decode($otherservice->images);
            if(!empty($timeline_images)){
                foreach ($timeline_images as $timeline_image) {
                    # code...
                    $image_path = public_path()."/uploads/otherservice/".$timeline_image;
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }
        }

        if ($otherservice){
            if ($otherservice->user_id == Auth::id()) {
                if ($otherservice->delete()) {
                    return redirect()->route('companyprofile')
                        ->with('success','Post deleted successfully');
                }
            }
        }

        return redirect()->route('companyprofile')
                        ->with('error','Error delete');
    }

    public function removeimage(Request $request)
    {
        $otherservice = Otherservice::where('user_id', Auth::id())->find($request->product_id);  
        $timeline_images = json_decode($otherservice->images);
        if(!empty($timeline_images)){
            $new_result = array_diff($timeline_images,[$request->image_name]);
            $new_result = array_values(array_filter($new_result));

            $image_path = public_path()."/uploads/otherservice/".$request->image_name;
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $otherservice->images = json_encode($new_result);
        $otherservice->save();
        return Response::json(array('success'=>true));
    }
}

