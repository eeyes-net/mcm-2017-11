@extends('index.layouts.master')

@section('main')
    <div class="mcm-match">
        <div id="pjax-container">
            <div class="row">
                @foreach ($matches as $match)
                    <div class="col-md-3 col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ $match->title }}</h3>
                            </div>
                            <div class="panel-body">
                                <p class="expires-at">报名截止日期 <span>{{ $match->expired_at }}</span></p>
                                @if (auth()->check() && in_array($match->id, $applied_matches_id))
                                    @if ($match->status === 'open')
                                        <button type="button" class="btn btn-default sign" data-toggle="modal" data-target="#sign">已报名</button>
                                    @else
                                        <button type="button" class="btn btn-default" disabled="disabled">已报名</button>
                                    @endif
                                @else
                                    @if ($match->status === 'open')
                                        <button type="button" class="btn btn-default available" data-toggle="modal" data-target="#sign">立即报名</button>
                                    @else
                                        <button type="button" class="btn btn-default" disabled="disabled">已截止</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $matches->links() }}
        </div>
    </div>
@stop
