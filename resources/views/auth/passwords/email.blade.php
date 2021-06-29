@extends('admin.layouts.layout-login')
@section('content')

<div class="container-fluid p-0">
    <div class="row no-gutters">
        <div class="col-xl-4">
            <div class="auth-full-page-content p-md-5 p-4">
                <div class="w-100">

                    <div class="d-flex flex-column h-100">
                        <div class="mb-4 mb-md-5 p-6">
                            <a class="d-block auth-logo">
                                <img src="{{asset('/assets/images/logo-dark.png')}}" alt="" height="18" class="auth-logo-dark">
                                <img src="{{asset('/assets/images/logo-light.png')}}" alt="" height="18" class="auth-logo-light">
                            </a>
                        </div>
                        <div class="my-auto">

                            <div>
                                <h5 class="text-primary">Reset Password</h5>
                                <p class="text-muted">Enter your email address that you used to register. We'll send you an email with your username and a link to reset your password</p>
                            </div>
                            @if ($message = Session::get('error'))

                                <div class="alert alert-danger">
                                    @foreach ($message as $data)
                                       {{$data}}
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-4">
        {{ Form::open(array('route' => 'password.email', 'method' => 'post', 'id' => 'sign_in', 'class' => 'form-horizontal')) }}

            @include('admin.components.messages')
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" value="{{ old('email') }}" id="email" placeholder="Enter Registered Email" aria-required="true"{{ $errors->has('email') ? ' aria-invalid="true"' : '' }}>
                @if ($errors->has('email'))
                    <label class="error" for="email">
                        <strong>{{ $errors->first('email') }}</strong>
                    </label>
                @endif
            </div>

            <div class="mt-3">
                <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">RESET MY PASSWORD</button>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ url('admin/login') }}" class="text-muted"><i class="mdi mdi-lock mr-1"></i> Sign In!</a>
            </div>

        {{ Form::close() }}

                            </div>
                        </div>

                        <div class="mt-4 mt-md-5 text-center">
                            <p class="mb-0">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>  OnSite Waste Technologies</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="auth-full-bg pt-lg-5 p-4">
                <div class="bg-overlay"></div>
                <div class="row justify-content-center">
                                <div class="col-lg-7">
                                    <div class="text-center testi-bg">

                                        <h4 class="mb-3"><i class="bx bxs-quote-alt-left text-primary h1 align-middle mr-3"></i><span class="text-primary">5k</span>+ Satisfied clients</h4>

                                        <div dir="ltr">
                                            <div>
                                                <div class="item">
                                                    <div class="py-3">
                                                        <p class="font-size-16 mb-4">"We love OnSite's ability to scale throughout the US. Our organization is adding locations, and OnSite Waste Technologies will grow alongside us!"</p>
                                                        <div>
                                                            <h4 class="font-size-16 text-primary">Director of Procurement</h4>
                                                            <p class="font-size-14 mb-0">-  Long-Term Care
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="py-3">
                                                        <p class="font-size-16 mb-4">"The TE-5000 from OnSite Waste Technologies is an easy-to-use solution for healthcare facilities. It saves time and promotes a safe and compliant environment for patients and staff</p>
                                                        <div>
                                                            <h4 class="font-size-16 text-primary">Sherrie Dornberger</h4>
                                                            <p class="font-size-14 mb-0">- Executive Director, NADONA</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
        <!-- end col -->


        <!-- end col -->
    </div>
    <!-- end row -->
</div>
@endsection

