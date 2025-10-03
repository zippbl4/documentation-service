@extends('confluence.layout')

@php
/**
* @see \App\Documentation\Viewer\Controllers\WikiController::edit
* @var $request \App\Documentation\Viewer\DTO\WikiRequestDTO
* @var $title string
* @var $content string
* @var $context ?\App\Context\Context
* @var $page string
*/
@endphp

@section('body')
  @include('confluence.common.editor', [
    'route' => route('wiki.update.page', ['lang' => $request->lang ]),
    'title' => $title,
    'content' => $content,
    'page' => $page,
  ])
@endsection
