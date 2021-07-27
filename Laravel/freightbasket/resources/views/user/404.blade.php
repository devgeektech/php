@extends('layouts.usertemplate')
@section('content')
<!-- Top Bar End -->
<!-- Page-Title -->
<div class="row">
	<div class="col-md-12">
		@if(!empty($data))
			<h3>{{$data}}</h3>
		@else
			<h3>Not Found</h3>
		@endif
	</div>
</div>
@endsection