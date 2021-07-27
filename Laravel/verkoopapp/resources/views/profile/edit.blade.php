@extends('layouts.app', ['title' => __('User Profile')])
@section('content')
<script src="{{ asset('public/js/admin/profile.js') }}"></script>
<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-xl-12 order-xl-1">
      <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
          <div class="row align-items-center">
            <h3 class="col-12 mb-0">{{ __('Edit Profile') }}</h3>
          </div>
        </div>
        <div class="card-body">
          @csrf
          <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <label class="col-sm-2 col-form-label" for="first_name">{{ __('First Name') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input type="hidden" id="user_id" value="{{ $admin->id }}">
                  <input type="text" name="first_name" id="first_name" class="form-control form-control-alternative" placeholder="{{ __('First Name') }}" value="{{ old('name', $admin->first_name) }}" required autofocus>
                  <span id="first_name-req" class="invalid-feedback" role="alert">
                    <strong>*required</strong>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label" for="last_name">{{ __('Last Name') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input type="text" name="last_name" id="last_name" class="form-control form-control-alternative" placeholder="{{ __('Name') }}" value="{{ old('name', $admin->last_name) }}" required autofocus>
                  <span id="last_name-req" class="invalid-feedback" role="alert">
                    <strong>*required</strong>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label" for="email">{{ __('Email') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input type="email" name="email" id="email" class="form-control form-control-alternative" placeholder="{{ __('Email') }}" value="{{ old('email', $admin->email) }}" required>
                  <span id="email-req" class="invalid-feedback" role="alert">
                    <strong>*required</strong>
                  </span>
                  <span id="email-error" class="invalid-feedback" role="alert">
                    <strong>*Enter a valid email</strong>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button onclick="updateInfo()" class="btn btn-success mt-4">{{ __('Save') }}</button>
          </div>
          <hr class="my-4"/>
          @csrf
          <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <label class="col-sm-2 col-form-label" for="current-password">{{ __('Current Password') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input type="hidden" id="user_id" value="{{ $admin->id }}">
                  <input type="password" name="old_password" id="current-password" class="form-control form-control-alternative" placeholder="{{ __('Current Password') }}" value="" required>
                  <span id="curr_pass_err" class="invalid-feedback" role="alert">
                    <strong>*Incorrect password</strong>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label" for="input-password">{{ __('New Password') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input type="password" name="password" id="password" class="form-control form-control-alternative" placeholder="{{ __('New Password') }}" value="" required>
                  <span id="password-req" class="invalid-feedback" role="alert">
                    <strong>*required</strong>
                  </span>
                  <span id="pass_length_err" class="invalid-feedback" role="alert">
                    <strong>*Password must be atleast 7 characters</strong>
                  </span>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-sm-2 col-form-label" for="confirm-password">{{ __('Confirm Password') }}</label>
              <div class="col-sm-7">
                <div class="form-group">
                  <input type="password" name="confirm-password" id="confirm-password" class="form-control form-control-alternative" placeholder="{{ __('Confirm Password') }}" value="" required>
                  <span id="confirm-req" class="invalid-feedback" role="alert">
                    <strong>*required</strong>
                  </span>
                  <span id="pass_match_err" class="invalid-feedback" role="alert">
                    <strong>*Password do not match</strong>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button onclick="updatePassword()" class="btn btn-success mt-4">{{ __('Change password') }}</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
	<div class="d-flex justify-content-center">
		<div class="spinner-border text-danger" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
  @include('layouts.footers.auth')
</div>
@endsection