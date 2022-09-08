@extends('layouts.admin')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
      <h6 class="m-0 font-weight-bold text-primary">Edit Comment </h6>
      <div class="ml-auto">
        <a href="{{url('admin/notifications_index')}}" class="btn btn-primary">
        <span class="icon-text-white-50">
            <i class="fa fa-home"></i>    
        </span>
        <span class="text">notifications</span>
    </a>
      </div>
    </div>
    
    </div class="card-body">
    @if ($notification_comment != null)
    {!! Form::model($notification, ['url' => 'admin/notifications_update/'.$notification_comment[0]['id'] .'/'. $id, 'method' => 'post', 'files' => true]) !!}
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', old('name', $notification_comment[0]['name']), ['class' => 'form-control']) !!}
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    
        <div class="col-12">
            <div class="form-group">
                {!! Form::label('email', 'Email') !!}
                {!! Form::text('email', old('email', $notification_comment[0]['email']), ['class' => 'form-control']) !!}
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    
        <div class="col-12">
            <div class="form-group">
                {!! Form::label('ip_address', 'ip_address') !!}
                {!! Form::text('ip_address', old('ip_address', $notification_comment[0]['ip_address']), ['class' => 'form-control']) !!}
                @error('ip_address')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {!! Form::label('status', 'status') !!}
                {!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], old('status', $notification_comment[0]['status']), ['class' => 'form-control']) !!}
                @error('status')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {!! Form::label('comment', 'comment') !!}
                {!! Form::textarea('comment', old('comment', $notification_comment[0]['comment']), ['class' => 'form-control']) !!}
                @error('comment')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>

    <div class="form-group pt-4">
        {!! Form::submit('Update notification', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
    @endif
    </div>
</div>

@endsection

