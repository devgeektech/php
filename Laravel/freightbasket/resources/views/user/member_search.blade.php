@extends('layouts.usertemplate')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                </div>
                <h4 class="page-title">Search Members</h4>
            </div>
            @if (Session::has('success'))
            <p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
            @endif
            @if (Session::has('error'))
            <p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>        
                @endforeach
            </div>
            @endif            
    		<div class="row">
    	        <div class="col-md-12">
    	            <div class="card">
    	                <div class="card-body">
        	               <table class="table table-bordered data-table" id="datatable">
    					        <thead>
                                <tr>
                                    <th>Profile</th>
                                    <th>Company Name</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @if(!empty($result))
                                @foreach($result as $tbl_data)                               
                                <tr>
                                    <td><a href="{{ route('user.search.member_profile', $tbl_data->id)}}"><img src="/uploads/profiles/{{$tbl_data->avatar}}" height="100px" width="100px" style="border-radius: 50px;"></a></td>
                                    <td>{{$tbl_data->company_details['companyname']}}</td>
                                    <td>{{$tbl_data->company_details['companycity']}}</td>
                                    <td>{{$tbl_data->company_details['companycountry']}}</td>
                                    <td>{{$tbl_data->company_details['companyemail']}}</td>
                                    <td>{{$tbl_data->company_details['companyphone']}}</td>
                                    <td>
                                    <a href="#" class="action-icons sendmemberEmail" data-toggle="modal" data-id="28" data-target="#sendmemberEmailModal-{{$tbl_data->id}}"><i class="fa fa-envelope" aria-hidden="true" title="Send Email"></i></a>
                                    <a href="{{ route('user.search.member_profile', $tbl_data->id)}}" class="action-icons" title="Open Profile"><i class="fa fa-eye  "></i></a>
                                    <a href="{{ route('messenger', $tbl_data->id)}}" class="action-icons" title="Send Message"><i class="fa fa-paper-plane  "></i></a>
                                    <div class="modal fade" id="sendmemberEmailModal-{{$tbl_data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('member_send_email') }}" method="post">
                                        @csrf
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" name="email" value="{{$tbl_data->company_details['companyemail']}}"required>
                                                    <small id="fileHelp" class="form-text text-muted"></small>
                                                </div>      
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                          </div>
                                        </div>
                                        </form>
                                  </div>
                                </div>                                                                       
                                </td>
                                </tr>                                
                                @endforeach
                                @endif                    
                            </tbody>
    				       </table>
    	                </div>
    	            </div>
    	        </div>
    	    </div>
        </div>
    </div>
@endsection
