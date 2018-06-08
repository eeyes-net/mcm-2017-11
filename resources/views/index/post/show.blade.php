@extends('index.layouts.master')

@section('title'){{ $post->title }}@endsection

@section('main')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1>{{ $post->title }}</h1>
            <p>发布时间：{{ $post->created_at }}</p>
            <div>{!! $post->content !!}</div>
        </div>
    </div>
@stop
