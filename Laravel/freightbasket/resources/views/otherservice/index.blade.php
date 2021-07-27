<tr id="other_service-{{ $other_service->id }}">
    <td>{{$loop->iteration}}</td>
    <td>{{ $other_service->name }}</td>
    <td>{{ $other_service->description }}</td>
    <td>
        <a href="{{ route('otherservice.show',$other_service->id) }}" class="action-icons" ><i class="fa fa-eye  "></i></a>
        @if($other_service->user_id == Auth::User()->id)
        <a href="{{ route('otherservice.edit',$other_service->id) }}" class="action-icons"><i class="fa fa-edit  "></i></a>
        <a href="{{ route('otherservice.delete',$other_service->id) }}" class="action-icons" ><i class="fa fa-trash-o  "></i></a>
        @endif
    </td>
</tr>
                