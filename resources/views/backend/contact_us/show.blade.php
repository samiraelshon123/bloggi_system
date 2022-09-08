@extends('layouts.admin')
    
@section('content')
    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
      <h6 class="m-0 font-weight-bold text-primary">{{$message->title}}</h6>
      <div class="ml-auto">
        <a href="{{url('admin/contact_us_index')}}" class="btn btn-primary">
        <span class="icon-text-white-50">
            <i class="fa fa-home"></i>    
        </span>
        <span class="text">Message</span>
    </a>
      </div>
    </div>
    
      <div class="table-responsive">
        <table class="table table-hover">
         
          <tbody>
            <tr>
                <td>Title</td>
                <td>{{$message->title}}</td>
            </tr>
            <tr>
                <td>From</td>
                <td>{{$message->name}} <{{$message->email}}></td>
            </tr>
            <tr>
                <td>Message</td>
                <td>{!!$message->message!!}</td>
            </tr>
          </tbody>
          
        </table>
      </div>
    
  </div>


  
@endsection