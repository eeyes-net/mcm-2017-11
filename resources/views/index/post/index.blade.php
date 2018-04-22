@extends('index.layouts.master')

@section('main')
    <div class="mcm-post">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body" id="pjax-container">
                        <h2 class="card-title">公告</h2>
                        <ul class="list-group list-group-flush">
                            @foreach ($posts as $post)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-10 mcm-post-title"><a href="{{ url('/post/' . $post->id) }}" data-post-id="{{ $post->id }}"><h3>{{ $post->title }}</h3></a></div>
                                        <div class="col-md-2 text-right mcm-post-date"><span title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</span></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>

        <index-post-modal v-model="modalShow" :post-id="postId" v-on:error="onError"></index-post-modal>
    </div>
@stop
