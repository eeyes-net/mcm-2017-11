@extends('index.layouts.master')

@section('main')
    <div class="mcm-post">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="mcm-post-content">
                    @include('index.post.layouts.content', [
                        'no_button' => true
                    ])
                </div>
            </div>
        </div>
    </div>
@stop
