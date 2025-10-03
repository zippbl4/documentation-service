@extends('no-name.layout')

@section('css')
    {!! $styles ?? '' !!}
@endsection

@section('js')
    {!! $scripts ?? '' !!}
@endsection

@section('body')
    {!! $body !!}
@endsection
