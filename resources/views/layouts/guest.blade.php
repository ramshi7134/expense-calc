<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f4f7f6;
        }

        .auth-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }

        .auth-card .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            background-color: var(--bs-primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    {{-- Adjusted column classes for a narrower card --}}
                    <div class="col-lg-12 col-md-6 col-sm-8 col-11">
                        <div class="card auth-card">
                            <div class="card-header">
                                {{ $header ?? config('app.name', 'Laravel') }}
                            </div>
                            <div class="card-body p-4">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
