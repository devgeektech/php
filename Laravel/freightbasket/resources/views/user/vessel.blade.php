@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Vessel List
                @if(Auth::User()->role_id == "4")
                <a href="{{ url('vessel/add') }}" class="btn btn-success float-right">Add Vessel </a>
                @endif
            </h4>
		</div>
		@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
		@if (Session::has('error'))
		<p class="alert alert-danger">{!! Session::get('error') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif


	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
		        <div class="row">
                    <table class="table table-stripped table-bordered" id="datatable">
    				    <thead>
    				        <tr>
    				            <th>Vessel Name</th>
    				            <th>Built Date</th>
    				            <th>Imo Number</th>
    				            <th>Mmsi</th>
    				            <th>Call Sign</th>
    				            <th>Flag</th>
    				            <th>Action</th>
    				        </tr>
    				    </thead>
    				    <tbody>
    				     
    				     @if($vessels->count() > 0)
        				     @foreach($vessels as $vessel)
        				        <tr>
        				         <td>{{ $vessel->vessel_name }}</td>
        				         <td>{{ $vessel->built_date }}  </td>
        				         <td>{{ $vessel->imo_no }}  </td>
        				         <td>{{ $vessel->nmsi }}  </td>
        				         <td>{{ $vessel->call_sign }}  </td>
        				         <td>{{ $vessel->flag }}  </td>
        				         <td>
        				            <a href="#" class="action-icons sendglobvesselemailModal" data-toggle="modal" data-id="{{ $vessel->id }}" data-target="#sendglobvesselemailModal"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
                                    <a href="{{ route('vessel.view', $vessel->id) }}" class="action-icons" ><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('vessel.edit', $vessel->id) }}" class="action-icons" ><i class="fa fa-edit"></i></a>
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
		        </div>
			</div>
		</div>
	</div>
</div>

<!-- The Modal -->
<div class="modal" id="sendglobvesselemailModal">
    <form method="post">
        <div class="modal-dialog">
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Vessel Details Mail</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
        
                <!-- Modal body -->
                <div class="modal-body form_inputs">
                    <label>Email Address</label>
                    <input type="hidden" name="vessel_id" id="vessel_id" value=""/>
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