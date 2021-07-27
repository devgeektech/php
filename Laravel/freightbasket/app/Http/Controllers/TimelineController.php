<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Timeline;
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

class TimelineController extends Controller
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
    public function create()
    {
        //
        $timeline = Timeline::where('user_id', Auth::id())->paginate(5);
        return view('user.userdashboard',compact('timeline'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
                'message' => 'required|unique:timelines,message',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ],
            [
              'message.unique'=> 'Name of the Post already exist.', // custom message
            ]
        );

        $request->merge([
            'images' => json_encode(null)
        ]);
        // dd($request);
        $timeline = Timeline::create($request->all());
        if($request->hasfile('images'))
         {
            foreach($request->file('images') as $file)
            {
                $name = uniqid().time().'.'.$file->extension();
                $file->move(public_path().'/uploads/timeline/', $name);  
                $data[] = $name;  
            }
            $timeline->images = json_encode($data);
         }

        $timeline->save();

        return redirect()->route('Udashboard')
                        ->with('success','Timeline created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function show(Timeline $timeline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timeline = Timeline::where('user_id', Auth::id())->find($id);        
        if(empty($timeline)){
            return redirect()->back();
        }else{
            return view('timeline.edit',compact('timeline','json_product_img'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $timeline = Timeline::where('user_id', Auth::id())->find($id);        
        $data = json_decode($timeline->images);
        $request->merge([
            'images' => '',
        ]);
        $timeline->update($request->all());
        if($request->hasfile('images'))
        {
            foreach($request->file('images') as $file)
            {
                $name = uniqid().time().'.'.$file->extension();
                $file->move(public_path().'/uploads/timeline/', $name);  
                $data[] = $name;  
            }
        }

        $timeline->images = json_encode($data);
        $timeline->save();
        return redirect()->route('Udashboard')
                        ->with('success','Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {   

        $response = array();
        $response['code'] = 400;

        $post = Timeline::find($request->input('id'));

        $timeline_images = json_decode($post->images);
        if(!empty($timeline_images)){
            foreach ($timeline_images as $timeline_image) {
                # code...
                $image_path = public_path()."/uploads/timeline/".$timeline_image;
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        }

        if ($post){
            if ($post->user_id == Auth::id()) {
                if ($post->delete()) {
                    $response['code'] = 200;
                }
            }
        }

        return Response::json($response);
    }

    public function removeimage(Request $request)
    {
        $timeline = Timeline::where('user_id', Auth::id())->find($request->product_id);  
        $timeline_images = json_decode($timeline->images);
        if(!empty($timeline_images)){
            $new_result = array_diff($timeline_images,[$request->image_name]);
            $new_result = array_values(array_filter($new_result));

            $image_path = public_path()."/uploads/timeline/".$request->image_name;
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        $timeline->images = json_encode($new_result);
        $timeline->save();
        return Response::json(array('success'=>true));
    }
}

