@extends('index.layouts.master')

@section('title')队伍招募@endsection

@section('main')
    <div class="mcm-recruit">
        <div class="d-flex mcm-recruit-controls">
            <div class="mr-auto">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle mcm-recruit-btn-dropdown" type="button" id="dropdownMenuRecruitTags" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ empty($tags) ? '全部' : implode(',', $tags)  }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuRecruitTags">
                        <a class="dropdown-item" href="/recruit">全部</a>
                        @foreach (config('mcm.recruit_tags') as $item)
                            <a class="dropdown-item" href="/recruit?tags={{ urlencode($item) }}">{{ $item }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary mcm-recruit-btn-create">发布招募</button>
            </div>
        </div>

        <div>
            <p>声明：这里仅提供发布招募的功能，请根据招募者提供的联系方式私下交流，然后由发起者在个人中心中的队伍管理处添加队员。</p>
        </div>

        <layouts-error :errors="errors"></layouts-error>

        <div id="pjax-container">
            <div class="row" id="list">
                @foreach ($recruits as $recruit)
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <?php $tags = explode(',', $recruit->tags); ?>
                                <h2 class="card-title">队伍招募
                                    @foreach ($tags as $tag)
                                        <span class="badge badge-info">{{ $tag }}</span>
                                    @endforeach
                                    <span class="badge badge-light float-right mcm-recruit-card-created-at" title="{{ $recruit->created_at }}">{{ $recruit->created_at->diffForHumans() }}</span>
                                </h2>
                                <div class="mcm-recruit-card-content">
                                    <div class="d-md-flex">
                                        <div class="flex-shrink-0">当前队员</div>
                                        <div>{{ $recruit->members }}</div>
                                    </div>
                                    <div class="d-md-flex">
                                        <div class="flex-shrink-0">队伍描述</div>
                                        <div>{{ $recruit->description }}</div>
                                    </div>
                                    <div class="d-md-flex">
                                        <div class="flex-shrink-0">联系方式</div>
                                        <div>{{ $recruit->contact }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $recruits->links() }}
        </div>

        <index-recruit-modal v-model="modalShow"></index-recruit-modal>
    </div>
@endsection
