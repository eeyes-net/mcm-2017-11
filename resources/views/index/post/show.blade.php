@extends('index.layouts.master')

@section('main')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1>{{ $post->title }}</h1>
            <p>{{ $post->created_at }}</p>
            <section>
                {!! $post->content !!}
            </section>
        </div>
    </div>
@stop
