@extends('layouts.usertemplate')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Special Rates Request</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('user.specialrate') }}">Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
       
    {{ Form::open(array('url' => $action)) }}
        {{ Form::hidden('service_category', substr(collect(request()->segments())->last(), 0, 3)) }}
        {{ Form::hidden('service_type', substr(collect(request()->segments())->last(), 4, 3)) }}
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Readyness Date</strong>
                    {{ Form::date('readyness_date', @$data->readyness_date, ['class' => 'form-control','placeholder' => 'Readyness Date', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('readyness_date') }}</span>
                </div>
                <div class="form-group">
                    <strong>Commodity Name</strong>
                    {{ Form::text('commodity_name', @$data->commodity_name, ['class' => 'form-control','placeholder' => 'Commodity Name', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('commodity_name') }}</span>
                </div>
                <div class="form-group">
                    <strong>Origin Country</strong>
                    {{ Form::text('origin_country', @$data->origin_country, ['class' => 'form-control','placeholder' => 'Origin Country', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('origin_country') }}</span>
                </div>
                <div class="form-group container row">
                    <strong class="row col-md-12">Place Of Collect</strong>
                    {{ Form::text('place_of_collect[address]', @$data->place_of_collect['address'], ['class' => 'form-control col-md-4','placeholder' => 'Address', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('place_of_collect_address') }}</span>

                    @if("sea-lcl" || "sea-fcl")
                    {{ Form::text('place_of_collect[zip]', @$data->place_of_collect['zip'], ['class' => 'form-control col-md-4','placeholder' => 'Zip Code', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('place_of_collect_zip') }}</span>
                    @endif

                    {{ Form::text('place_of_collect[city]', @$data->place_of_collect['city'], ['class' => 'form-control col-md-4','placeholder' => 'City', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('place_of_collect_city') }}</span>
                </div>
                
                @if(collect(request()->segments())->last() == "sea-lcl" || collect(request()->segments())->last() == "sea-fcl")
                <div class="form-group">
                    <strong>Loading Port</strong>
                    {{ Form::text('loading_port', @$data->loading_port, ['class' => 'form-control','placeholder' => 'Loading Port', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('loading_port') }}</span>
                </div>
                @endif

                @if(collect(request()->segments())->last() == "land-ftl" || collect(request()->segments())->last() == "land-ltl")
                <div class="form-group">
                    <strong>Domestic Custom Office</strong>
                    {{ Form::text('domestic_custom_office', @$data->domestic_custom_office, ['class' => 'form-control','placeholder' => 'Domestic Custom Office', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('domestic_custom_office') }}</span>
                </div>
                @endif

                @if(collect(request()->segments())->last() == "air")
                <div class="form-group">
                    <strong>Domestic Airport</strong>
                    {{ Form::text('domestic_airport', @$data->domestic_airport, ['class' => 'form-control','placeholder' => 'Domestic Airport', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('domestic_airport') }}</span>
                </div>
                @endif
                
                <div class="form-group">
                    <strong>Destination Country</strong>
                    {{ Form::text('destination_country', @$data->destination_country, ['class' => 'form-control','placeholder' => 'Destination Country', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('destination_country') }}</span>
                </div>

                @if(collect(request()->segments())->last() == "sea-lcl" || collect(request()->segments())->last() == "sea-fcl" || collect(request()->segments())->last() == "air")
                <div class="form-group">
                    <strong>Destination Port</strong>
                    {{ Form::text('destination_port', @$data->destination_port, ['class' => 'form-control','placeholder' => 'Destination Port', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
                @endif

                <div class="form-group">
                    <strong>Final Place Of Delivery</strong>
                    {{ Form::text('final_place_of_delivery', @$data->final_place_of_delivery, ['class' => 'form-control','placeholder' => 'Final Place Of Delivery', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('final_place_of_delivery') }}</span>
                </div>
                <div class="form-group">
                    <strong>Packing Type</strong>
                    {{ Form::text('packing_type', @$data->packing_type, ['class' => 'form-control','placeholder' => 'Packing Type', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('packing_type') }}</span>
                </div>
                <div class="form-group">
                    <strong>Number Of Quantity</strong>
                    {{ Form::number('number_of_qty', @$data->number_of_qty, ['class' => 'form-control','placeholder' => 'Number Of Quantity', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('number_of_qty') }}</span>
                </div>
                <div class="form-group">
                    <strong>Dimension Of the Cargo</strong>
                    {{ Form::text('dimensions_cargo', @$data->dimensions_cargo, ['class' => 'form-control','placeholder' => 'Dimension Of the Cargo', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('dimensions_cargo') }}</span>
                </div>

                @if(collect(request()->segments())->last() == "land-ftl")
                    <div class="form-group">
                        <strong>Trailer Type</strong>
                        {{ Form::text('trailer_types', @$data->trailer_types, ['class' => 'form-control','placeholder' => 'Trailer Type', 'required' => '']) }}
                        <span class="text-danger">{{ $errors->first('trailer_types') }}</span>
                    </div>
                @endif

                @if(collect(request()->segments())->last() == "sea-fcl")
                    <div class="form-group">
                        <strong>Cntr Type</strong>
                        {{ Form::text('cntr_types', @$data->cntr_types, ['class' => 'form-control','placeholder' => 'Cntr Type', 'required' => '']) }}
                        <span class="text-danger">{{ $errors->first('cntr_types') }}</span>
                    </div>
                @endif

                <div class="form-group">
                    <strong>Gross Weight</strong>
                    {{ Form::number('gross_weight', @$data->gross_weight, ['class' => 'form-control','placeholder' => 'Gross Weight', 'required' => '']) }}
                    <span class="text-danger">{{ $errors->first('gross_weight') }}</span>
                </div>
                <div class="form-group container row">
                    <strong class="row col-md-12">Dangerous Cargo</strong>
                    {{ Form::number('dangerous_cargo[imo_no]', @$data->dangerous_cargo['imo_no'], ['class' => 'form-control col-md-3','placeholder' => 'IMO NO', 'required' => '']) }}
                    {{ Form::text('dangerous_cargo[class]', @$data->dangerous_cargo['class'], ['class' => 'form-control col-md-3','placeholder' => 'Class', 'required' => '']) }}
                    {{ Form::number('dangerous_cargo[un_no]', @$data->dangerous_cargo['un_no'], ['class' => 'form-control col-md-3','placeholder' => 'UN No', 'required' => '']) }}
                    {{ Form::text('dangerous_cargo[flash_point]', @$data->dangerous_cargo['flash_point'], ['class' => 'form-control col-md-3','placeholder' => 'Flash Point', 'required' => '']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                {{Form::submit('submit', ["class"=>"btn btn-primary"])}}
            </div>
        </div>
       
    {{ Form::close() }}
    @endsection