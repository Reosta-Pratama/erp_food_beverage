@extends('layouts.guest', [
    'title' => 'Reset Password',
    'bodyClass' => 'bg-guest'
])

@section('styles')
@endsection

@section('content')

    <div class="auth container">
        <div class="background">
            <img src="{{ asset('assets/images/auth-background.png') }}" alt="Background Auth">
        </div>

        <div class="row justify-content-center align-items-center">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-6 col-sm-8 col-12">
                <div class="card custom my-4">
                    <div class="card-body p-5">
                        <div class="logo">
                            <img src="{{ asset('assets/images/logo/logo-transparent.webp') }}" alt="Logo">
                        </div>

                        <div class="info">
                            <h4 class="fw-semibold">Forgot Password</h4>
                            <p class="text-muted">Recover access to your account quickly and easily!</p>
                        </div>
                        
                        <form class="row gy-3">
                            <div class="col-12">
                                <label class="form-label text-default" for="email">
                                    Email
                                </label>
                                <div class="position-relative">
                                    <input
                                        class="form-control"
                                        id="email"
                                        placeholder="Enter the email address.."
                                        type="text">
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 mt-2">Recover</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection