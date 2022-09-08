@extends('layouts.admin')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
          <h6 class="m-0 font-weight-bold text-primary">Users</h6>
          <div class="ml-auto">
            <a href="{{url('admin/users_create')}}" class="btn btn-primary">
            <span class="icon-text-white-50">
                <i class="fa fa-plus"></i>
            </span>
            <span class="text">Add New User</span>
        </a>
          </div>
        </div>
        @include('backend.users.filter.filter')
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Email & Mobile</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th class="text-center" style="width: 30px">Action</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            @if ($user->user_image != '')
                                <img src="{{ asset('assets/users/' . $user->user_image) }}" width="60">

                            @else
                                <img src="{{ asset('assets/users/default.jpeg') }}" width="60">
                            @endif
                        </td>
                        <td>
                            <a href="{{url('admin/users_show/'.$user->id)}}">{{$user->name}}</a>
                            <p class="text-gray-400"><b>{{$user->name}}</b></p>
                        </td>
                        <td>
                            {{$user->email}}
                            <p class="text-gray-400"><b>{{$user->mobile}}</b></p>
                        </td>
                        <td>{{ $user->status() }}</td>
                        <td>{{$user->created_at->format('d-m-Y h:i a')}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{url('admin/users_edit/'.$user->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="if (confirm('Are you sure to delete this user?')) {document.getElementById('user-delete-{{$user->id}}').submit();} else {return false;}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                <form action="{{url('admin/users_destroy/'.$user->id)}}" method="post" id="user-delete-{{$user->id}}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="6" class="text-center">No Users found</td>
                  </tr>
              @endforelse
              </tbody>
              <tfoot>
              <tr>
                  <th colspan="6">
                      <div class="float-right">
                        {!! $users->appends(request()->input())->links() !!}
                      </div>
                  </th>
              </tr>
              </tfoot>
          </table>
          </div>

      </div>
@endsection
