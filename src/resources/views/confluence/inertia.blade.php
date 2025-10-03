@extends('confluence.layout')

@section('menu')
  @push('css')
    <style>
      .main-content {
        margin: 60px 0 40px 0;
      }
    </style>
  @endpush
@endsection

@push('js')
  @vite('resources/js/app.js')
@endpush

@push('css')
  @vite('resources/js/app.css')
@endpush

@section('body')
  @inertia
@endsection
