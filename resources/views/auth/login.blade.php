@extends('layouts.login')
@section('content')
        <!--login page start-->
        <div class="authentication-main">
            <div class="row">
                <div class="col-md-4 p-0">
                    <div class="auth-innerleft">
                        <div class="text-center">
                            <img src="{{asset('images/logo_new.png')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-md-8 p-0">
                    <div class="auth-innerright">
                        <div class="authentication-box">
                            <h4>LOGIN</h4>
                            <h6>Enter your Username and Password For Login</h6>
                            <div class="card mt-4 p-4 mb-0">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Username (Mobile Number)</label>
                                        <input id="mobile" type="tel" class="form-control form-control-lg @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autofocus>
                                        @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Password</label>
                                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="checkbox p-0">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">Remember me</label>
                                    </div>
                                    <div class="form-group form-row mt-3 mb-0">
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-secondary">LOGIN</button>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="text-left mt-2 m-l-20">
                                                Not Register Yet?&nbsp;&nbsp;<a href="#" class="btn-link text-capitalize">Register Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--login page end-->
@endsection
