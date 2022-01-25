<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>{{ env('APP_NAME') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
    <style>
        .midContent, .midContent {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .display-4 {
            font-weight:bold;
            font-size:calc(1.475rem + 0.7vw);
        }
        body {
            background-color: #f4f6f8;
        }
    </style>
</head>
<body>
@extends('shopify-app::layouts.default')

@section('content')
    <!-- You are: (shop domain name) -->
    <!-- <p>You are: {{ $shopDomain ?? Auth::user()->name }}</p> -->
    
    <div class="midContent">
        <div class="container">
            <div class="row welcomePage align-items-center">
                <div class="col-md-4">
                    <div class="welcomePLeft">
                        <h4 class="display-4">Welcome to<br /> {{ env('APP_NAME') }}</h4>
                        <p>Click Start Setup below, choose your discount settings and you're ready to boost your sales.</p>
                        <a type="button" class="btn btn-danger btn-lg" href="{{ env('APP_URL').'/start-setup' }}">Start Setup</a>
                    </div>
                </div>
                <div class="col-md-5 offset-md-3">
                    <div class="welcomePRight">
                        <img src="{{ asset('assets/images/order-confirmed-concept-illustration_114360-1486.jpg') }}" class="img-fluid"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>
@endsection

</body>
</html>