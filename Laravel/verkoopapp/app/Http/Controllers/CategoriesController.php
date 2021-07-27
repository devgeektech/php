<?php

namespace App\Http\Controllers;

use App\ApiV1categories;
use Illuminate\Http\Request;
use App\Categories as Category;
use Illuminate\Http\Response;
use Exception;

class CategoriesController extends Controller
{
     private $categories = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $mainCategories = $this->getCategories();
            $data = $this->getChieldCategories($mainCategories);
            if($data){
                return response()->json(['data' => $data, 'message'=>'Data Get Successfully.'], Response::HTTP_OK);        
            }else{
                return response()->json(['data' => [], 'message'=>'Data Not Found.'], Response::HTTP_OK);    
            }
            
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);     
        }
        
        //return categories::collection($this->getChieldCategories($mainCategories));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
        return new Categroy($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
     /**
     * Get all category 
     */
    public static function getCategories(){
            return Category::select('id','name','parent_id','image')->where('parent_id',0)->get();
    }
    /**
     * Get all subCategory 
     */
    private function getChieldCategories($data){
        foreach ($data as $key => $value) {
            $parent_id = $value->id;
            $alldata = Category::select('id','name','parent_id','image')->where('parent_id',$parent_id)->get();
            if(!empty($alldata)){
                $this->getChieldCategories($alldata);
                if($value->image){
                    $value->image = url('/').'/'.$value->image;                    
                }
                $value->sub_category = $alldata;
                if($value->parent_id == "0"){
                    $this->categories[] = $value;
                }               
            } 
        }
        return $this->categories;        
    }
}
