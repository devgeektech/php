@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')
<script src="{{ asset('public/js/admin/dashboard.js') }}"></script>
<div class="container-fluid mt--7">
  <div class="row pb-5">
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-header bg-transparent">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
              <h2 class="mb-0">Items listed</h2>
            </div>
            <div class="col">
              <ul class="nav nav-pills justify-content-end">
                <li class="nav-item mr-2 mr-md-0">
                  <a class="nav-link py-2 px-3 active" onclick="showHideData('')" data-toggle="tab">
                  <span class="d-none d-md-block">Month</span>
                  <span class="d-md-none">M</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link py-2 px-3" onclick="showHideData('week')" data-toggle="tab">
                  <span class="d-none d-md-block">Week</span>
                  <span class="d-md-none">W</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link py-2 px-3" onclick="showHideData('today')" data-toggle="tab">
                  <span class="d-none d-md-block">Today</span>
                  <span class="d-md-none">W</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <div class="chart" style="height: calc(100vh - 300px);">
            <table id="tnxsTable" class="table align-items-center table-flush">
              <thead>
                <th></th>
                <th>Item ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Type</th>
                <th>Category</th>
                <th>Created At</th>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection