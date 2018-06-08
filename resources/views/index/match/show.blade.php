@extends('index.layouts.master')

@section('title'){{ $match->title }}@endsection

@section('main')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1>{{ $match->title }}</h1>
            <p>{{ $match->expired_at }}</p>
            <p>{{ $match->status }}</p>
        </div>
    </div>
@endsection
