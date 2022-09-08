@extends('layouts.admin_auth')

@section('content')
<div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
            <div class="col-lg-6">
                
              <div class="p-5">
                
                <div class="text-center">
                    
                  <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                </div>
                <form action="{{url('admin/loginAdmin')}}" class="" method="post">
                    @csrf
                  <div class="form-group">
                    <input type="text" class="form-control form-control-user"   placeholder="Enter User Name" name="username">
                    @error('username')<span class="text-danger">{{$message}}</span>@enderror
                    </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" name="password">
                    @error('password')<span class="text-danger">{{$message}}</span>@enderror
                   </div>
                  
                  <input type="submit" value="Login" class="btn btn-primary btn-user btn-block">

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@endsection