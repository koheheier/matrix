<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <link rel="stylesheet" href="{{ asset('/css/nav.css')  }}" >
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>@yield('title')</title>
    </head>
    <body>
    <nav>
      <ul class="ubannerlist">
          @auth
              <li class="bannerlist p-4"><a href="/list" method="get" class="navlist">一覧画面に移動します</a></li>
              <li class="bannerlist p-4"><a href="/make_matrix" method="get" class="navlist">作成画面に移動します</a></li>
          @endauth
          @if(!\Illuminate\Support\Facades\Auth::id())
              <li class="bannerlist p-4"><a href="http://localhost/login" method="get" class="navlist">ログイン</a></li>
          @endif
        </ul>
    </nav>
      @yield('main')
    </body>
</html>
