@extends('index.layouts.master')

@section('main')
    <div class="mcm-match">
        <layouts-error :errors="errors"></layouts-error>

        <div id="pjax-container">
            <div class="row">
                @foreach ($matches as $match)
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">{{ $match->title }}</h2>
                                <p class="mcm-match-expires-at">报名截止日期 <span>{{ $match->expired_at }}</span></p>
                                @if (auth()->check() && array_key_exists($match->id, $applied_matches_id))
                                    @if ($match->is_available && in_array($applied_matches_id[$match->id], $leading_teams_id))
                                        <button type="button" class="btn btn-danger mcm-match-btn-cancel" data-match-id="{{ $match->id }}" data-match-title="{{ $match->title }}">取消报名</button>
                                    @else
                                        <button type="button" class="btn btn-primary" disabled="disabled" title="只有队长可以取消报名">已报名</button>
                                    @endif
                                @else
                                    @if ($match->is_available)
                                        <button type="button" class="btn btn-outline-primary mcm-match-btn-available" data-match-id="{{ $match->id }}" data-match-title="{{ $match->title }}">立即报名</button>
                                    @else
                                        <button type="button" class="btn btn-secondary" disabled="disabled">已截止</button>
                                    @endif
                                @endif
                                <span class="mcm-match-team-count">已有 <span>{{ $match->teams_count }}</span> 支队伍报名</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $matches->links() }}
        </div>

        <index-match-modal :match="match" v-model="modalShow" v-on:ok="onApplyOk"></index-match-modal>
    </div>
@stop
