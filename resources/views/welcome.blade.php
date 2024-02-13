<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @if (config('backpack.base.meta_robots_content'))
            <meta name="robots" content="{{ config('backpack.base.meta_robots_content', 'noindex, nofollow') }}">
        @endif
        <title>{{ env('APP_NAME') }}</title>
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: auto;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
                padding: 60px 20px 60px;
                min-height: 100%;
            }

            .title {
                max-width: 450px;
                margin: 0 auto;
            }

            .title img {
                max-width: 100%;
                height: auto;
            }

            .links > a {
                color: #fff;
                padding: 0 25px;
                font-size: 28px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: capitalize;
                text-shadow: -1px 0 #c42c39, 0 2px #c42c39, 2px 0 #c42c39, 0 -1px #c42c39;
            }

            .links > a:hover {
                color: #cdd5d7;
            }

            .text {
                color: #120704;
                font-size: 11px;
                font-weight: bold;
                margin-top: 10px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="links m-b-md">
                    <a href="{{ backpack_url('dashboard') }}">Delivery service</a>
                </div>
                <div class="title">
                    <img src="{{ asset('img/promo.jpg') }}" alt="{{ env('APP_NAME') }}">
                </div>
                <div class="text">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
        </div>
    </body>
</html>
