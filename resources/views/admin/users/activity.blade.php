@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row mb-4">
    <div class="col-md-12">
      <h4 class="mb-0">User Activity</h4>
    </div>
  </div>
  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>User</th>
            <th>Action</th>
            <th>Date</th>
            <th>IP Address</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John Doe</td>
            <td>Logged in</td>
            <td>2026-07-12 10:30</td>
            <td>192.168.1.100</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
