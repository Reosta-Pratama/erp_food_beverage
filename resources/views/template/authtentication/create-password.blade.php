@extends('layouts.guest', [
    'title' => 'Create Password',
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
                            <h4 class="fw-semibold">Reset Password</h4>
                            <p class="text-muted">Set your new password here!</p>
                        </div>
                        
                        <form class="row gy-3">
                            <div class="col-12">
                                <label class="form-label text-default" for="current-password">
                                    Current Password
                                </label>
                                <div class="password">
                                    <input
                                        class="form-control create-password-input"
                                        id="current-password"
                                        placeholder="Enter the current password.."
                                        type="password">
                                    <button
                                        class="show-password-button"
                                        type="button"
                                        data-target="current-password">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-default" for="new-password">
                                    New Password
                                </label>
                                <div class="password">
                                    <input
                                        class="form-control create-password-input"
                                        id="new-password"
                                        placeholder="Enter the new password.."
                                        type="password">
                                    <button
                                        class="show-password-button"
                                        type="button"
                                        data-target="new-password">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-default" for="confirm-password">
                                    Confirm Password
                                </label>
                                <div class="password">
                                    <input
                                        class="form-control create-password-input"
                                        id="confirm-password"
                                        placeholder="Enter the confirm password.."
                                        type="password">
                                    <button
                                        class="show-password-button"
                                        type="button"
                                        data-target="confirm-password">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 mt-2">Reset</button>
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
