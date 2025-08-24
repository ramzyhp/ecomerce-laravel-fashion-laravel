@extends('frontend.layouts.master')

@section('title', 'Prolific || Login Page')

@section('main-content')
    <div class="login-container position-relative min-vh-100 d-flex align-items-center justify-content-center p-3">
        <!-- Background image -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark">
            <img src="{{ asset('backend/img/login_page.png') }}" alt="Background"
                class="w-100 h-100 object-fit-cover opacity-60">
        </div>

        <!-- Login modal -->
        <div class="login-modal bg-white p-4 rounded shadow position-relative"
            style="max-width: 450px; width: 100%; z-index: 10;">
            <h2 class="text-center mb-2">Login</h2>
            <p class="text-center mb-4">Pengguna baru? <a href="{{ route('register.form') }}">Daftar Akun</a></p>

            <!-- Form -->
            <form class="form" method="post" action="{{ route('login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required="required"
                        placeholder="Masukkan email anda..." value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required="required"
                            placeholder="Masukkan password anda..." value="{{ old('password') }}">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                </div>

                <button type="submit" class="btn w-100 mb-3" style="background-color: #222; color: white;">Login</button>

                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a class="lost-pass d-block mt-3" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    </div>
                @endif
            </form>
            <!--/ End Form -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .login-form {
            max-width: 400px;
            /* Limit the width for better mobile view */
            width: 100%;
            /* Full width on mobile */
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1rem;
                /* Adjust padding for smaller screens */
            }
        }
    </style>
@endpush
