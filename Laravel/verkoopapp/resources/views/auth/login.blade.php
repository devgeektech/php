@extends('layouts.app', ['class' => 'bg-img', 'login' => false])
@section('content')
<script src="{{ asset('public/js/login.js') }}"></script>
<div class="container mt-8 pb-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <div class="card red-shadow border-0">
        <div class="card-body px-lg-5 py-lg-5">
          <div class="text-center text-muted mb-4">
            <img src="{{ asset('public/images/favicon.png') }}">
          </div>
          @csrf
          <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-email-83 app-text-default"></i></span>
              </div>
              <input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="" name="email" value="{{ old('email') }}" required>
            </div>
            @if ($errors->has('email'))
            <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
          </div>
          <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-lock-circle-open app-text-default"></i></span>
              </div>
              <input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>
            </div>
            @if ($errors->has('password'))
            <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>
          <!-- <div class="custom-control custom-control-alternative custom-checkbox">
            <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
            <label class="custom-control-label" for="customCheckLogin">
                <span class="text-muted">{{ __('Remember me') }}</span>
            </label>
            </div> -->
          <div class="text-center">
            <button id="but_submit" class="btn app-btn-primary my-4">{{ __('Sign in') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
