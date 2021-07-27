@extends('layouts.usertemplate')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Special Rates Request</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" data-toggle="modal" data-target="#specialraterequest" href="#"> Add Rates</a>


                <!-- Modal For Background image upload start here-->
                <div class="modal fade" id="specialraterequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Select The service you want to add.</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                                <div class="dropdown">
                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    Select Service
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('user.addrate','air') }}">Air</a>
                                    <a class="dropdown-item" href="{{ route('user.addrate','sea-lcl') }}">Sea - LCL</a>
                                    <a class="dropdown-item" href="{{ route('user.addrate','sea-fcl') }}">Sea - FCL</a>
                                    <a class="dropdown-item" href="{{ route('user.addrate','land-ftl') }}">Land - FTL</a>
                                    <a class="dropdown-item" href="{{ route('user.addrate','land-ltl') }}">Land - LTL</a>
                                  </div>
                                </div>  
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </div>
                    </div>
                </div>
                <!-- Modal For Background image upload End here-->
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered data-table" id="datatable">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Shipment Mode</th>
                            <th>Cargo</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($specialraterequest as $specialraterequest1)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$specialraterequest1->readyness_date}}</td>
                            <td>{{$specialraterequest1->origin_country}}</td>
                            <td>{{$specialraterequest1->destination_country}}</td>
                            <td>{{$specialraterequest1->service_category}}</td>
                            <td>
                                @if(!empty($specialraterequest1->service_type))
                                    {{$specialraterequest1->service_type}}
                                @else
                                    Air
                                @endif
                            </td>
                            <td class="row container">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success" aria-label="Left Align">Submit Offer</button>
                                </div>
                                <div class="col-md-6">
                                {!! Form::open(['method' => 'POST', 'route' => "user.specialrate.destroy"]) !!}
                                    {{ Form::hidden('id', $specialraterequest1->id) }}
                                    <button type="submit" class="btn btn-dark" aria-label="Left Align">Remove</button>
                                {!! Form::close() !!}
                                </div>
                                <!--a href="{{ route('user.specialrate.view', $specialraterequest1->id) }}" class="action-icons" ><i class="fa fa-eye"></i></a-->
                                <!--a href="{{ route('user.specialrate.edit', $specialraterequest1->id) }}" class="action-icons" ><i class="fa fa-edit"></i></a-->
                                
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

                            
