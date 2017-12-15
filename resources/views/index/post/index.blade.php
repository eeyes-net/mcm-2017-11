@extends('index.layouts.master')

@section('main')
    <div class="mcm-post">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">公告</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group list-striped">
                            @foreach ($posts as $post)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-9 title"><a href="/post/{{ $post->id }}" data-toggle="modal" data-target="#announcement" value="{{ $post->id }}">{{ $post->title }}</a></div>
                                        <div class="col-md-3 text-right date"><span title="{{ $post->created_at }}">{{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
