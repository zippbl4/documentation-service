<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confluence Clone</title>
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('/views/confluence/css/app.css') }}">
  @stack('css')
</head>
<body>
<!-- Шапка -->
<header class="top-header">
  <div class="logo">
    <i class="fab fa-confluence"></i>
    <span>Confluence</span>
  </div>
  <div class="header-controls">
    @stack('top-menu')
    @auth
      <div class="dropdown-wrapper">
        <button class="create-btn" onclick="toggleMenu('createMenu')">
          <i class="fas fa-plus"></i>
          Создать
        </button>
        <div class="dropdown-menu" id="createMenu">
          @php
            $request = request();
            if (isset($request->page)) {
                $route = route('wiki.create.page', ['lang' => $request->lang, 'page' => $request->page]);
            } else {
                $route = route('wiki.create.root.page', ['lang' => request()->lang]);
            }
          @endphp
          <a href="{{ $route }}" class="dropdown-item">
            <i class="fas fa-file"></i>
            Страницу
          </a>
        </div>
      </div>
    @endauth
    <div class="dropdown-wrapper">
      <div class="user-menu-trigger" onclick="toggleMenu('userMenu')">
        <i class="fas fa-user-circle fa-lg"></i>
      </div>
      @guest
        <div class="dropdown-menu" id="userMenu">
          <a href="{{ route('auth.login') }}" class="dropdown-item">
            <i class="fas fa-sign-in-alt"></i>
            {{ __('Login') }}
          </a>
        </div>
      @else
        <div class="dropdown-menu" id="userMenu">
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i>
            {{ Auth::user()->name }}
          </a>
          <a href="#" class="dropdown-item">
            <i class="fas fa-cog"></i>
            Настройки
          </a>
          <a class="dropdown-item"
             href="{{ route('auth.logout') }}"
             onclick="event.preventDefault();document.getElementById('logout-form').submit();"
          >
            <i class="fas fa-sign-out-alt"></i>
            {{ __('Logout') }}
            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </a>
        </div>
      @endguest
    </div>
  </div>
</header>

<!-- Показывать этот блок, если дочерняя страница НЕ определила секцию "menu" -->
@unless(View::hasSection('menu'))
  <!-- Боковое меню -->
  @include('confluence.common.sidebar')
@endunless

<!-- Основной контент -->
<main class="main-content">
  @yield("body")
</main>

<!-- Подвал -->
<footer class="footer">
  <span>© 2024 Confluence Clone</span>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="{{ asset('/views/confluence/js/app.js') }}"></script>
@stack('js')
</body>
</html>
