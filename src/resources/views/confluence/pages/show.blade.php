@extends('confluence.layout')

@php
/**
* @see \App\Documentation\Viewer\Controllers\WikiController::show
* @var $request \App\Documentation\Viewer\DTO\WikiRequestDTO
* @var $title string
* @var $content string
* @var $context ?\App\Context\Context
*/
@endphp

@push('top-menu')
  @auth
    <div class="dropdown-wrapper">
      <button class="create-btn" onclick="location.href='{{ route('wiki.edit.page', ['lang' => $request->lang, 'page' => $request->page]) }}'">
        <i class="fas fa-edit"></i>
        Редактировать
      </button>
    </div>
  @endauth
@endpush

@section('body')
  <div class="ql-content-render">
    @isset($title)<h1>{{ $title }}</h1>@endisset
    <div class="ql-editor">
      {!! $content !!}
    </div>
  </div>
@endsection
