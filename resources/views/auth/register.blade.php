<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h2 fw-bold">{{ __('Create an Account') }}</h1>
                            <p class="text-muted">Join us! It's quick and easy.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="form-floating mb-3">
                                <input id="name" class="form-control @error('name') is-invalid @enderror"
                                    type="text" name="name" placeholder="John Doe" value="{{ old('name') }}"
                                    required autofocus autocomplete="name" />
                                <label for="name">{{ __('Name') }}</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-floating mb-3">
                                <input id="email" class="form-control @error('email') is-invalid @enderror"
                                    type="email" name="email" placeholder="name@example.com"
                                    value="{{ old('email') }}" required autocomplete="username" />
                                <label for="email">{{ __('Email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input id="password" class="form-control @error('password') is-invalid @enderror"
                                    type="password" name="password" placeholder="Password" required
                                    autocomplete="new-password" />
                                <label for="password">{{ __('Password') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-floating mb-4">
                                <input id="password_confirmation" class="form-control" type="password"
                                    name="password_confirmation" placeholder="Confirm Password" required
                                    autocomplete="new-password" />
                                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>

                        <div class="text-center my-4">
                            <span class="text-muted">OR</span>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('google.redirect') }}" class="btn btn-google btn-lg">
                                <i class="fab fa-google"></i> {{ __('Sign up with Google') }}
                            </a>
                        </div>

                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small">
                            <a href="{{ route('login') }}">Have an account? Go to login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
