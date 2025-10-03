@php
  /**
  * @var $route string
  * @var $title ?string
  * @var $content string
  * @var $parent ?string
  * @var $page ?string
  */
@endphp

@push("css")
  <link rel="stylesheet" href="{{ asset('/views/confluence/css/editor.css') }}">
@endpush

@push("js")
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <script src="{{ asset('/views/confluence/js/editor.js') }}"></script>
  @isset($content)
    <script>
      const value = '{!! addslashes($content) !!}'

      quill.root.innerHTML = value;
      quill.update()

      // v2
      //quill.clipboard.dangerouslyPasteHTML(value)

      // v3
      // const delta = quill.clipboard.convert(value)
      // quill.setContents(delta, 'silent')
      // quill.update()

    </script>
  @endisset
@endpush

{{-- Объявляя секцию menu мы ее убираем из отрисовки --}}
@section('menu')
  @push('css')
    <style>
      .main-content {
        margin: 60px 0 40px 0;
      }
    </style>
  @endpush
@endsection

@section('body')
  <div class="editor-wrapper">
    <div class="editor-header">
      <a href="{{ url()->previous() }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Назад
      </a>
      <div class="editor-actions">
        <button type="submit" form="pageForm" class="btn btn-save">
          <i class="fas fa-save"></i> Сохранить
        </button>
      </div>
    </div>

    <form id="pageForm" action="{{ $route }}" method="POST">
      <!-- Поле для заголовка -->
      <input type="text"
             name="title"
             class="title-input"
             placeholder="Заголовок страницы"
             value="{{ $title ?? '' }}">

      <!-- Контейнер для тулбара Quill -->
      <div id="toolbar"></div>

      <!-- Контейнер для редактора -->
      <div id="editor"></div>

      <!-- Скрытые поля для данных -->
      <input type="hidden" name="content" id="hidden-content">

      @csrf
      @isset($parent)<input name="parent" id="hidden-parent" style="display: none;" value="{{ $parent }}">@endif
      @isset($page)<input name="page" id="hidden-page" style="display: none;" value="{{ $page }}">@endif
    </form>
  </div>
@endsection
