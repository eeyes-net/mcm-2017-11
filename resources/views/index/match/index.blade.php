@extends('index.layouts.master')

@section('main')
    <div class="mcm-match">
        <div class="row">
            @foreach ($matches as $match)
                <div class="col-md-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ $match->title }}</h3>
                        </div>
                        <div class="panel-body">
                            <p class="expires-at">报名截止日期 <span>{{ $match->expired_at }}</span></p>
                            @if ($match->status === 'open')
                                <button type="button" class="btn btn-default available" data-toggle="modal" data-target="#sign">立即报名</button>
                            @else
                                <button type="button" class="btn btn-default" disabled="disabled">已截止</button>
                            @endif
                            {{--<button type="button" class="btn btn-default sign" disabled="disabled" >已报名</button>--}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $matches->links() }}
    </div>
@stop
