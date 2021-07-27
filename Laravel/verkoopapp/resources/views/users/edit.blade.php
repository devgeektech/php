@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])
@section('content')
<script src="{{ asset('public/js/admin/users.js') }}"></script>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        @csrf
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ __('Edit User') }}</h4>
            <p class="card-category"></p>
          </div>
          <div class="card-body ">
            <div class="row">
              <div class="col-md-12 text-right">
                <a href="{{ url('/')}}/admin/users" class="btn btn-sm btn-danger">{{ __('Back to list') }}</a>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label">{{ __('Username') }}</label>
              <div class="col-sm-7">
                <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                  <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" id="username" type="text" placeholder="{{ __('*Username') }}" required="true" value="{{ old('username', $user->username) }}" aria-required="true"/>
                  <span id="username-req" class="error text-danger hide" for="username">*required</span>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label">{{ __('Email') }}</label>
              <div class="col-sm-7">
                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                  <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" type="email" value="{{ old('email', $user->email) }}" placeholder="{{ __('*Email') }}" required />
                  <span id="email-req" class="error text-danger hide" for="email">*required</span>
                  <span id="email-error" class="error text-danger hide" for="email">*enter a valid email</span>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label" for="input-password">{{ __('New Password') }}</label>
              <div class="col-sm-7">
                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                  <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" input type="password" name="password" id="password" placeholder="{{ __('New Password') }}" value="" required />
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label" for="input-password-confirmation">{{ __('Confirm Password') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input class="form-control" name="confirm-password" id="confirm-password" type="password" placeholder="{{ __('Confirm Password') }}" value="" required />
                  <span id="confirm-error" class="error text-danger hide" for="confirm-password">*Password not matched</span>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer ml-auto mr-auto text-center">
            <p class="text-center mt-4">Account Status</p>
            <div class="custon-toggle-btn">
              <Span class="font-14">Deactivated</Span>
              <label class="switch">
              <input type="checkbox" id="is_active" name="is_active" {{ $user->is_active == 1 ? 'checked' : '' }}>
              <span class="slider round"></span>
              </label>
              <span class="font-14">Approved</span>
            </div>
            <button onclick="updateUser()" class="btn btn-success mt-4">{{ __('Save') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
