<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cotizador Conoce</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="background-color: #f3f4f6; margin: 0;">
    <div id="app">
        <quote-form></quote-form>
    </div>
</body>

</html>
