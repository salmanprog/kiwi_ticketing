<!DOCTYPE html>
<html lang="{{ @Helper::currentLanguage()->code }}" dir="{{ @Helper::currentLanguage()->direction }}">
<head>
    @include('dashboard.layouts.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body class="auth_app_bg">
<div class="app auth_app" id="app">
    @yield('content')
</div>
@include('dashboard.layouts.foot')
</body>
</html>
