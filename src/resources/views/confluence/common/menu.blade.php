<div class="has-children">
  @foreach($items as $item)
    <div class="has-children">
      @if(! $item->hasDescendants())
        {{-- Элемент без детей (файл) --}}
        <a href="{{ $item->getRoute() }}" class="nav-item">
          <i class="fas fa-file file-icon"></i>
          {{ $item->title }}
        </a>
      @else
        {{-- Элемент с детьми (папка) --}}
        <a href="{{ $item->getRoute() }}" class="nav-item">
          <i class="fas fa-folder folder-icon" onclick="event.preventDefault(); toggleSubmenu(this)"></i>
          {{ $item->title }}
          <i class="menu-toggle fas fa-chevron-down" onclick="event.preventDefault(); toggleSubmenu(this)"></i>
        </a>
        <ul class="submenu">
          @foreach($item->descendants as $child)
            <li class="{{ $child->hasDescendants() ? 'has-children' : '' }}">
              @if($child->hasDescendants())
                {{-- Рекурсивный вызов для вложенных элементов --}}
                @include('confluence.common.menu', ['items' => collect([$child])])
              @else
                <a href="{{ $child->getRoute() }}" class="nav-item">
                  <i class="fas fa-file file-icon"></i>
                  {{ $child->title }}
                </a>
              @endif
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  @endforeach
</div>
