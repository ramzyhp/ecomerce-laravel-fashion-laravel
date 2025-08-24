@extends('frontend.layouts.master')

@section('title', 'Prolific || Register Page')

@section('main-content')
    <div class="login-container position-relative min-vh-100 d-flex align-items-center justify-content-center p-3">
        <!-- Background image -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark">
            <img src="{{ asset('backend/img/login_page.png') }}" alt="Background"
                class="w-100 h-100 object-fit-cover opacity-60">
        </div>

        <!-- Register modal -->
        <div class="login-modal bg-white p-4 rounded shadow position-relative"
            style="max-width: 450px; width: 100%; z-index: 10;">
            <h2 class="text-center mb-2">Daftar Akun</h2>
            <p class="text-center mb-4">Sudah punya akun? <a href="{{ route('login.form') }}">Login</a></p>

            <!-- Form -->
            <form class="form" method="post" action="{{ route('register.submit') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required="required"
                        placeholder="Masukkan nama anda..." value="{{ old('name') }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required="required"
                        placeholder="Masukkan email anda..." value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nama Pengguna <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="username" name="username" required="required"
                        placeholder="Masukkan nama pengguna anda..." value="{{ old('username') }}">
                    @error('username')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (min 8 karakter) <span
                            class="text-danger">*</span></label>
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
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            required="required" placeholder="Konfirmasi password anda..."
                            value="{{ old('password_confirmation') }}">
                        <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="d-flex gap-2 align-items-center mb-3">
                    <button class="btn w-100" type="submit" style="background-color: #222; color: white;">Daftar
                        Akun</button>
                </div>
            </form>
            <!--/ End Form -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password visibility
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

        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .btn-facebook {
            background-color: #39579A;
            color: white;
        }

        .btn-facebook:hover {
            background-color: #073088 !important;
        }

        .btn-dark {
            background-color: #444444;
            color: white;
        }

        .btn-dark:hover {
            background-color: black !important;
        }

        .btn-danger {
            background-color: #ea4335;
            color: white;
        }

        .btn-danger:hover {
            background-color: rgb(243, 26, 26) !important;
        }

        .login-form {
            max-width: 400px;
            width: 100%;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1rem;
            }
        }
    </style>
@endpush
