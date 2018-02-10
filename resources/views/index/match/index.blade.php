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
                                        <button type="button" class="btn btn-default sign" data-toggle="modal" data-target="#sign" disabled="disabled">已报名</button>
                                    @else
                                        <button type="button" class="btn btn-default" disabled="disabled">已报名</button>
                                    @endif
                                @else
                                    @if ($match->status === 'open')
                                        <button type="button" class="btn btn-default available" data-toggle="modal" data-target="#sign" data-match-id="{{ $match->id }}">立即报名</button>
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

        <div class="modal fade" id="sign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title announcement-title" id="myModalLabel">
                            （比赛名称）
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger"></div>
                        <form role="form">
                            <input type="hidden" name="match_id" value="0">
                            <div class="form-group">
                                <label>选择队伍</label>
                                <select name="team_id"></select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn">报名</button>
                                <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
