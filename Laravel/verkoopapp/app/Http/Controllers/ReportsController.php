<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user_reports;
use App\reports;
use Illuminate\Http\Response;
use App\Http\Requests\CreateuserReportRequest;
use App\Items;
use App\User;
use Exception;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = reports::all();
        if ($reports) {
            return response()->json(['message' => 'All report list get successfully.', 'data' => $reports], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Data not found.'], Response::HTTP_OK);
        }
    }

    public function getReportList($type)
    {
        $reports = reports::where('type', $type)->get();
        if ($reports) {
            return response()->json(['message' => 'All report list get successfully.', 'data' => $reports], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Report list not found.'], Response::HTTP_OK);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDisputesReport()
    {
        $reports = user_reports::getAllDisputeReport();
        $disputes = array();
        foreach ($reports as $value) {
            $dispute = $value;
            if ($value['type'] == 1) {
                $reported_user = User::find($value['item_id']);
                $dispute['reported_type'] = 'User';
                $dispute['reported_item'] = $dispute['user_name'] = $reported_user['first_name'];
                $dispute['reported_email'] = $reported_user['email'];
            } else {
                $item = Items::getItemDetail($value['item_id']);
                $dispute['reported_type'] = 'Item';
                $dispute['reported_item'] = $item['name'];
                $dispute['user_name'] = $item['first_name'];
                $dispute['reported_email'] = $item['email'];
            }
            $dispute['reported_on'] = date('d-m-Y', strtotime($value['reported_on']));
            array_push($disputes, $dispute);
        }

        return view('admin/disputes', ['disputes' => $disputes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateuserReportRequest $request)
    {
        try {
            $user_reports = new user_reports();
            $user_reports->item_id = $request->item_id;
            $user_reports->user_id = $request->user_id;
            $user_reports->report_id = $request->report_id;
            $user_reports->type = $request->type;
            $user_reports->save();
            if ($user_reports) {
                return response()->json(['message' => 'Reporting is done successfully.'], Response::HTTP_CREATED);
            } else {
                return response()->json(['message' => 'Please try again.'], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //   try{
      //       $Comments = comments::find($id);
      //       $delete = $Comments->delete();
      //       if($delete){
      //           return response()->json(['message'=> 'Comments delete successfully.'], Response::HTTP_OK);
            // }else{
            // 	return response()->json(['message'=> 'Comments deleted failed.'], Response::HTTP_NOT_FOUND);
            // }
      //   }catch(Exception $e){
      //       return response()->json(['message'=> $e], Response::HTTP_INTERNAL_SERVER_ERROR);
      //   }
    }
}
