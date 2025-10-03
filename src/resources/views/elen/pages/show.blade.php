@extends('elen.layout')

@section('css')
    {!! $styles ?? '' !!}
@endsection

@section('js')
    {!! $scripts ?? '' !!}
@endsection

@section('body')
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                {!! $body !!}
            </div>
        </div>
    </section>
@endsection
