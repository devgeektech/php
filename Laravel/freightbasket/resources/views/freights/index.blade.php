<tr id="freight-{{ $freight->id }}">
 	<td>{{ $freight->id }}</td>
 	<td>{{ $freight->service_category }}</td>
     
 	<td>{{ $freight->departure_port }}</td>
 	<td>{{ $freight->arriaval_port }}</td>
 	<td>{{ $freight->freightvalidity }} 
    
        @if($freight->freightvalidity >= date('m/d/Y'))
        <span class="text-success">Active</span>
        
        @else
        <span class="text-danger">Expired</span>
        @endif
     
 	</td>
 	<td>
		<a href="{{ route('singlefreight',$freight->id) }}" class="action-icons" ><i class="fa fa-eye  "></i></a>
 		@if($freight->user_id == Auth::User()->id)
		<a href="{{ route('editfreight',$freight->id) }}" class="action-icons"><i class="fa fa-edit  "></i></a>
		<a href="{{ route('deletefreight',$freight->id) }}" class="action-icons" ><i class="fa fa-trash-o  "></i></a>
		<a href="{{ route('clonefreight',$freight->id) }}" class="action-icons"><i class="fa fa-clone" aria-hidden="true"></i></a>
		@endif
 	</td>
</tr>
		     	