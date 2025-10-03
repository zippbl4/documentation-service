<?php
/** @var \App\Menu\DTO\MenuDTO $menu */
/** @var \Illuminate\Support\Collection|\App\Menu\Entities\Menu[] $items */
?>

<ul>
  @foreach($items as $item)
    <li
      class="{{ $item->isChildLoaded() ? "loaded open" : '' }} {{ $menu->url === $item->href ? "current" : '' }}"
    >
      <a
        href="{{ route(...$item->route) }}"
        data-id="{{ $item->id }}"
        data-has-children="{{ $item->hasChild() }}"
        data-child-loaded="{{ $item->isChildLoaded() }}"
      >
        @if($item->hasChild())<span class="arrow">▶</span> @endif{{ $item->title }}
      </a>

      @if($item->isChildLoaded())
        @include('no-name.common.menu', ['items' => $item->children, 'menu' => $menu])
      @endif
    </li>
  @endforeach
</ul>

