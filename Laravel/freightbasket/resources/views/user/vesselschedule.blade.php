@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Vessel Schedule List<a href="{{ url('vessel/schedule/add/') }}" class="btn btn-success float-right">Add Vessel Schedule</a></h4>
		</div>
		@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif


	</div>
</div>
@if(Auth::User()->role_id == '4')
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
		        <div class="row"  style="overflow-x:auto;">
                    <table class="table table-stripped table-bordered text-uppercase">
    				    <thead>
    				        <tr>
    				            <th>Vessel Name</th>    				            
                                <th>voyage no</th>
                                <th>Departure Country</th>
                                <th>Arrival Country</th>
                                <th>Loading Date</th>
                                <th>Cut Off Date</th>
                                <th>Action</th>
    				        </tr>
    				    </thead>
    				    <tbody>
    				     
    				     @if($vessels->count() > 0)
    				        @foreach($vessels as $vessel)
                            <tr>
        				        <th>{{ $vessel->vessel_name }}</th>
                                <th>{{ $vessel->voyage_no }}</th>
                                <th>{{ $vessel->departure_country }}</th>
                                <th>{{ $vessel->arrival_port }}</th>
                                <th>{{ $vessel->loading_date }}</th>
                                <th>{{ $vessel->cut_off_date }}</th>
                                <td>
                                    <a href="#" class="action-icons sendvesselemailModal" data-toggle="modal" data-id="{{ $vessel->vsID }}" data-target="#sendvesselemailModal"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
                                    <a href="{{ route('vessel.schedule.view', $vessel->vsID) }}" class="action-icons" ><i class="fa fa-eye  "></i></a>
                                    <a href="{{ route('vessel.schedule.edit', $vessel->vsID) }}" class="action-icons"><i class="fa fa-edit  "></i></a>
                                    <a href="{{ route('vessel.schedule.delete', $vessel->vsID) }}" class="action-icons" ><i class="fa fa-trash-o  "></i></a>
                                    <a href="{{ route('vessel.schedule.clone', $vessel->vsID) }}" class="action-icons"><i class="fa fa-clone" aria-hidden="true"></i></a>
                                    
                                </td>
                            </tr>
    				        @endforeach
    				     @else
    				     <tr>
    				         <td colspan="6">No data Found </td>
    				     </tr>
    				     @endif
    				    </tbody>
    				</table>
                    <div class="float-right">
                        {{ $vessels->links() }}
                    </div>
		        </div>
			</div>
		</div>
	</div>
</div>
<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->
@else
<div class="card">
    <div class="card-body">
        <h3>Provider Dashboard is under maintenance</h3>
    </div>
</div>
@endif
<!-- The Modal -->
<div class="modal fade" id="sendvesselemailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post">
        <div class="modal-dialog">
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Send Vessel Schedule Mail</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
        
                <!-- Modal body -->
                <div class="modal-body form_inputs">
                    <label>Email Address</label>
                    <input type="hidden" name="vessel_schedule_id" id="vessel_schedule_id" value=""/>
                    <input type="email" class="form-control email" name="email" required/>
                </div>
                <div style="display:none" class="modal-body emailsent_scus">
                    <label>Email sent successfully.</label>
                </div>
        
                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary send_email_btn" value="send"/>  
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
        
            </div>
        </div>
    </form>
</div>
@endsection