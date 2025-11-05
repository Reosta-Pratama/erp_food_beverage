@extends('layouts.guest', [
    'title' => 'Sign Up',
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
                            <h4 class="fw-semibold">Sign Up</h4>
                            <p class="text-muted">Create an account and start your journey today!</p>
                        </div>
                        
                        <form class="row gy-3">
                            <div class="col-12">
                                <label class="form-label text-default" for="fullname">
                                    Full Name
                                </label>
                                <div class="position-relative">
                                    <input
                                        class="form-control"
                                        id="fullname"
                                        placeholder="Enter the full name.."
                                        type="text">
                                </div>
                            </div>

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
                                <button class="btn btn-primary w-100 mt-2">Create Account</button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Already have an account?
                                    <a href="{{ route('template.signIn') }}" class="text-primary fw-medium">
                                        Sign In
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
