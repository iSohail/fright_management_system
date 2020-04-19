<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <style>
        [v-cloak]>* {
            display: none;
        }

        [v-cloak]::before {
            content: " ";
            display: block;
            position: absolute;
            top: calc(50% - 40px);
            left: calc(50% - 40px);
            width: 80px;
            height: 80px;
            background-image: url('{{ asset('storage/loader.gif') }}');
            transition:
        }

        body {
            background: #121212;
        }
    </style>
</head>

<body>
    <div id="app" v-cloak>
        <app />
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>