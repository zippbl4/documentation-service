<?php /** @var \App\Menu\Composers\MenuComposer $menu */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>
  <link rel="stylesheet" href="{{ asset('/views/no-name/css/app.css') }}">
  @stack('css')
</head>
<body>


<div class="top-menu">
  <div class="logo">{{ config('app.name', 'Laravel') }}</div>
  <nav>
    @guest
      <a href="{{ route('auth.login') }}">{{ __('Login') }}</a>
    @else
      <a href="#">{{ Auth::user()->name }}</a>
      <a class="dropdown-item"
        href="{{ route('auth.logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();
      ">{{ __('Logout') }}</a>

      <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    @endguest
  </nav>
</div>
<div class="layout">
  <div>
    <div class="menu">
      <h2>Navigation</h2>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Spaces</a></li>
        <li><a href="#">Pages</a></li>
        <li><a href="#">Settings</a></li>
      </ul>
      <?php /** @var \App\Menu\Services\MenuService $menu */ ?>
      @isset($menu)
        <h2>Resources</h2>
        {!! $menu(request()) !!}
      @endisset
    </div>
  </div>
  <div class="content">
    @yield('body')
  </div>
</div>



@stack('js')
</body>
</html>
