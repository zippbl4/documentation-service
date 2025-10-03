@extends('confluence.layout')

@php
  /**
  * @see \App\Documentation\Viewer\Controllers\WikiController::create
  * @var $lang string
  * @var $page ?string
  */
@endphp

@section('body')
  @include('confluence.common.editor', [
    'route' => route('wiki.store.page', ['lang' => $lang]),
    'parent' => $page,
  ])
@endsection
