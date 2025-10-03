@extends('no-name.layout')

@push('css')
  @vite('resources/js/app.js')
@endpush

@section('body')
  @inertia
@endsection
