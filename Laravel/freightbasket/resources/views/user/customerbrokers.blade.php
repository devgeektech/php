@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
			</div>
			<h4 class="page-title">Services</h4>
		</div>
			@if (Session::has('success'))
		<p class="alert alert-success">{!! Session::get('success') !!}<span class="close" style="cursor:pointer;" data-dismiss="alert">&times;</span></p>
		@endif
	</div>
</div>
@if(Auth::User()->role_id == '4')

<div class="row">
  <div class="col-lg-12">
    <div class="row">
  
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-stripped table-bordered">
              <tbody>
                  <tr><td>
                    @foreach($results_companydetails as $results_companydetail)
                      @foreach( unserialize($results_companydetail->companyservice) as $key_companydetlls => $results_companydtl)
                        <h4>{{ str_replace("'","", $key_companydetlls) }}</h4>
                        
                        @foreach( $results_companydtl as $results_companydtl_fnl)
                          <p>{{ $results_companydtl_fnl }}</p>
                        @endforeach

                      @endforeach
                    @endforeach
                   </td></tr>
              </tbody>
          </table>
          <p class="float-right"></p>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <h3>Provider Dashboard is under maintenance</h3>
    </div>
</div>
@endif
<!-- testing modal -->
@endsection