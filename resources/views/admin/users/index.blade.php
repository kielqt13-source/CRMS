@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row mb-4">
    <div class="col-md-12">
      <h4 class="mb-0">Manage Users</h4>
    </div>
  </div>
  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name1</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John Doe</td>
            <td>john@example.com</td>
            <td><span class="badge bg-info">User</span></td>
            <td><button class="btn btn-sm btn-primary">Edit</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
