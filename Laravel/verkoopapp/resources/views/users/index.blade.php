@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])
@section('content')
<link href="{{ asset('public/css/users.css') }}">
<script src="{{ asset('public/js/admin/users.js') }}"></script>
<div class="content">
  <div class="loader"></div>
  <div id="overlay"></div>
  <div class="container-fluid mt--7">
    <div class="row">
      <div class="col-md-12">
        <div class="col mb-5">
          <div class="card-header card-header-primary" style="margin-top: 10%; margin-bottom: 15px; border-radius: .375rem;">
            <p class="card-category"> {{ __('Here you can manage users') }}</p>
          </div>
          <div class="card shadow">
            <!-- @if (session('status'))
              <div class="row">
                <div class="col-sm-12">
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="material-icons">close</i>
                    </button>
                    <span>{{ session('status') }}</span>
                  </div>
                </div>
              </div>
              @endif -->
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col-2">
                  <h3 class="mb-0">Users</h3>
                </div>
                <div class="col-6">
                  <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input class="form-control" id="searchUser" placeholder="Search Username OR Email OR Date" type="text">
                    </div>
                  </div>
                </div>
                <div class="col-4 text-right">
                  <a href="{{ url('/')}}/admin/user/create" class="btn btn-sm btn-success">Add user</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table id="usersTable" class="table align-items-center table-flush">
                <thead class="thead-light">
                  <th>
                    {{ __('ID') }}
                  </th>
                  <th>
                    {{ __('Name') }}
                  </th>
                  <th>
                    {{ __('Email') }}
                  </th>
                  <th class="text-center">
                    {{ __('Creation date') }}
                  </th>
                  <th class="text-center">
                    {{ __('active Status') }}
                  </th>
                  <th class="text-center">
                    {{ __('Actions') }}
                  </th>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr id="{{ $user->id }}">
                    <td>
                      {{ $user->id }}
                    </td>
                    <td>
                      {{ $user->first_name }}
                    </td>
                    <td>
                      {{ $user->email }}
                    </td>
                    <td class="text-center">
                      {{ $user->created_at->format('Y-m-d') }}
                    </td>
                    <td class="text-center">
                      @if ($user->is_active)
                      <a class="btn btn-icon-only text-black font-18" href="javascript:void(0)" role="button">
                        <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                      </a>
                      @else
                      <a class="btn btn-icon-only text-black font-18" href="javascript:void(0)" role="button">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                      </a>
                      @endif
                    </td>
                    <td class="text-center">
                      <a class="btn btn-icon-only text-black" href="{{ url('/')}}/admin/user/edit/{{ $user->id }}" role="button">
                      <i class="fa fa-edit" aria-hidden="true"></i>
                      </a>
                      <a class="btn btn-icon-only text-black" onclick="deleteUser({{ $user->id }})" role="button">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    document.getElementById("overlay").style.display = "none";
    $("#searchUser").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#usersTable tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
@endsection
