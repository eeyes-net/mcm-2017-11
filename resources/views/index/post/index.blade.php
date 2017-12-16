@extends('index.layouts.master')

@section('main')
    <div class="mcm-post">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">公告</h3>
                    </div>
                    <div class="panel-body" id="pjax-container">
                        <ul class="list-group list-striped">
                            @foreach ($posts as $post)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-9 title"><a href="/post/{{ $post->id }}" data-post-id="{{ $post->id }}">{{ $post->title }}</a></div>
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

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">
                            （公告标题）
                        </h4>
                        <span class="post-date">（发布日期）</span>
                    </div>
                    <div class="modal-body">
                        <div class="post-content">
                            （公告内容）
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
