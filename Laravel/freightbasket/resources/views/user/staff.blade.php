@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Manage Staff</h4>
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
				<table class="table table-stripped table-bordered">
				    <thead>
				        <tr>
				            <th>S No.</th>
				            <th>Name</th>
				            <th>Phone Number</th>
				            <th>Email</th>
				            <th>Status</th>
				            <th>Action</th>
				        </tr>
				    </thead>
				    <tbody>
				        @if( $numberofemployee !="")<?php $no = 1;?>
				        @foreach($staff as $row )
				         <tr>
				             <td>{{ $no }}</td>
				            <td>{{ $row->name }}</td>
				            <td>{{ $row->phone }}</td>
				            <td>{{ $row->email }}</td>
				            <td>@if($row->status == 0 )
				                Deactive 
				                @else
				                Active
				                @endif
				            </td>
				            <td>
				                 <a href="{{ route ( 'singlestaff',['id'=>$row->id] ) }}" class="action-icons"><i class="fa fa-edit"></i></a>
				                 <a href="{{ route ( 'deletestaff',['id'=>$row->id] ) }}" class="action-icons"><i class="fa fa-trash-o"></i></a>
				                
				            </td>
				        </tr>
				        <?php $no++; ?>
				        @endforeach
				        @else
				        <tr>
				            <td colspan="5">No Users Found</td>
				        </tr>
				        @endif
				       
				    </tbody>
				</table>
				<p class="float-right">{{ $staff->onEachSide(5)->links() }}</p>
			</div>
		</div>
	</div>
</div>
<!-- testing modal -->
<!-- Button trigger modal -->
<!-- Modal -->
@endsection