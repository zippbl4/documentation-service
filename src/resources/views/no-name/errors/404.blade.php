@extends('no-name.layout')

@section('body')
  {{ $code ?? '404'}} | {{ $message ?? __('Not Found') }}
@endsection
