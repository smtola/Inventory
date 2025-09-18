<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Noto+Sans+Khmer:wght@100..900&display=swap');
        *{
            font-family: 'Noto Sans Khmer', sans-serif;
        }
        .container{max-width:1200px;margin:0 auto;padding:1rem}
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="app-name" content="Book SMS" />
    <meta name="locale" content="{{ app()->getLocale() }}" />
    <link rel="icon" href="/favicon.ico">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#0ea5e9">
</head>
<body class="bg-gray-50 text-gray-900">
    @livewireStyles
    <livewire:pos />
    @livewireScripts
</body>
</html>