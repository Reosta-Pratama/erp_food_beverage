@extends('layouts.guest', [
    'title' => 'Sign In',
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
                            <h4 class="fw-semibold">Sign In</h4>
                            <p class="text-muted">Log in to continue your journey!</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form class="row gy-3"
                            action="{{ route('login.submit') }}" method="POST">
                            @csrf
                            <div class="col-12">
                                <label class="form-label text-default" for="email">
                                    Email
                                </label>
                                <div class="position-relative">
                                    <input
                                        class="form-control"
                                        id="email"
                                        placeholder="Enter the email address.."
                                        type="text"
                                        name="email">
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
                                        type="password"
                                        name="password">
                                    <button
                                        class="show-password-button"
                                        type="button"
                                        data-target="create-password">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberCheck">
                                    <label class="form-check-label" for="rememberCheck">Remember me</label>

                                    <a href="{{ route('template.resetPassword') }}" class="float-end link-danger fw-medium fs-12">Forget password ?</a>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 mt-2" type="submit">Sign In</button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Not registered?
                                    <a href="{{ route('template.signIn') }}" class="text-primary fw-medium">
                                        Sign Up
                                    </a>
                                </p>
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
