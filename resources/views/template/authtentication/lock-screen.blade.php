@extends('layouts.guest', [
    'title' => 'Lock Screen',
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
                            <h4 class="fw-semibold">Hello Bro!</h4>
                            <p class="text-muted">Welcome Back!</p>
                        </div>
                        
                        <form class="row gy-3">
                            <div class="col-12">
                                <div class="d-flex gap-2 align-items-center mb-3">
                                    <div class="lh-1">
                                        <span class="avatar avatar-sm avatar-rounded">
                                            <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="">
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-dark fw-medium">reosta.pane@gmail.com</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-default" for="create-password">
                                    Password
                                </label>
                                <div class="password">
                                    <input
                                        class="form-control create-password-input"
                                        id="create-password"
                                        placeholder="Enter the password.."
                                        type="password">
                                    <button
                                        class="show-password-button"
                                        type="button"
                                        data-target="create-password">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 mt-2">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/create-password-custom.js',
    ])

@endsection
