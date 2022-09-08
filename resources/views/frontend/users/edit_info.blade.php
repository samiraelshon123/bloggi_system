@extends('layouts.app')
@section('content')
    <!-- Start Blog Area -->

                <div class="col-lg-9 col-12">
                   <h3>Update Information</h3>
                    <form action="{{url('user/update_info')}}" method="post" name="user_info" id="user_info" files= "true">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input class="form-control" type="text" name= "name" value="{{auth()->user()->name}}">
                                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input class="form-control" type="text" name= "email" value="{{auth()->user()->email}}">
                                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Mobile</label>
                                    <input class="form-control" type="text" name= "mobile" value="{{auth()->user()->mobile}}">
                                    @error('mobile')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Recieve Email</label>
                                   <select name="recieve_email" id="" value="{{auth()->user()->recieve_email}}">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    @error('recieve_email')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Bio</label>
                                    <textarea name="bio" id="" cols="" rows="" class="form-control" name="bio" value= "{{auth()->user()->bio}}"></textarea>
                                    @error('bio')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if (auth()->user()->user_image != '')
                                <div class="col-12">
                                    <img src="{{asset('assets/users/'.auth()->user()->user_image)}}" class="img-fluid" width="150" alt="{{auth()->user()->name}}">
                                </div>
                            @endif
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">User Image</label>
                                    <input type="file" class="custom-file" name="user_image">
                                    @error('user_image')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Update Information" name="update_information">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h3>Update Password</h3>
                    <form action="{{url('user/update_password')}}" method="post" name="user_password" id="user_password">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">Current Password</label>
                                    <input class="form-control" type="password" name= "current_password" >
                                    @error('current_password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">New Password</label>
                                    <input class="form-control" type="password" name= "password" >
                                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="">Password Confirmation</label>
                                    <input class="form-control" type="password" name= "password_confirmation" >
                                    @error('password_confirmation')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Update Password" name="update_password">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                    @include('partial.frontend.users.sidebar')
                </div>
            
    <!-- End Blog Area -->
@endsection
