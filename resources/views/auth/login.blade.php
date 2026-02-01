<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h2 fw-bold">{{ __('Login to Your Account') }}</h1>
                            <p class="text-muted">Welcome back! Please enter your details.</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-floating mb-3">
                                <input id="email" class="form-control @error('email') is-invalid @enderror"
                                    type="email" name="email" placeholder="name@example.com"
                                    value="{{ old('email') }}" required autofocus autocomplete="username" />
                                <label for="email">{{ __('Email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input id="password" class="form-control @error('password') is-invalid @enderror"
                                    type="password" name="password" placeholder="Password" required
                                    autocomplete="current-password" />
                                <label for="password">{{ __('Password') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <!-- Remember Me -->
                                <div class="form-check">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Log in') }}
                                </button>
                            </div>

                            <div class="text-center my-4">
                                <span class="text-muted">OR</span>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('google.redirect') }}" class="btn btn-google btn-lg">
                                    <i class="fab fa-google"></i> {{ __('Sign in with Google') }}
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small">
                            <a href="{{ route('register') }}">Need an account? Sign up!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
