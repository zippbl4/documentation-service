<nav class="left-sidebar">
  <div class="nav-section">
    <div class="nav-title">Навигация</div>
    <a href="#" class="nav-item">
      <i class="fas fa-home folder-icon"></i>
      Главная
    </a>
    <a href="{{ route('nova.login') }}" class="nav-item">
      <i class="fas fa-home folder-icon"></i>
      Nova
    </a>
    <a href="#" class="nav-item">
      <i class="fas fa-clock folder-icon"></i>
      Недавние
    </a>
    <a href="#" class="nav-item">
      <i class="fas fa-star folder-icon"></i>
      Избранное
    </a>
  </div>

  @isset($menu)
    <div class="nav-section">
      <div class="nav-title">Дерево страниц</div>
      {!! $menu(request()) !!}
    </div>
  @endisset
</nav>
