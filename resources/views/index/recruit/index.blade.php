@extends('index.layouts.master')

@section('main')
    <div class="mcm-recruit">
        <div class="row options">
            <div class="col-sm-6">
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown">
                        <span class="tags">{{ empty($tags) ? '全部' : implode(',', $tags)  }}</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="/recruit">全部</a>
                        </li>
                        @foreach (config('mcm.recruit_tags') as $item)
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="/recruit?tags={{ $item }}">{{ $item }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 text-md-right">
                <button type="button" class="btn btn-default create-recruit">发布招募</button>
            </div>
        </div>

        <div class="row tips">
            <div class="col-sm-12">
                <div class="content">
                    <p>声明：这里仅提供发布招募的功能，请根据招募者提供的联系方式私下交流，然后由发起者在个人中心中的队伍管理处添加队员。</p>
                </div>
            </div>
        </div>

        <div id="pjax-container">
            <div class="row" id="list">
                @foreach ($recruits as $recruit)
                    <div class="col-md-3 col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span class="panel-title">队伍招募</span>
                                <?php $tags = explode(',', $recruit->tags); ?>
                                @foreach($tags as $tag)
                                    <span class="label label-info">{{ $tag }}</span>
                                @endforeach
                                <p class="created-at">{{ $recruit->created_at }}</p>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12 item-title">
                                        当前队员
                                    </div>
                                    <div class="col-lg-8 col-md-6 col-sm-12 item-content">
                                        {{ $recruit->members }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12 item-title">
                                        队伍描述
                                    </div>
                                    <div class="col-lg-8 col-md-6 col-sm-12 item-content">
                                        {{ $recruit->description }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12 item-title">
                                        联系方式
                                    </div>
                                    <div class="col-lg-8 col-md-6 col-sm-12 item-content">
                                        {{ $recruit->contact }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $recruits->links() }}
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title announcement-title" id="myModalLabel">
                            队伍招募
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">您填写的成员尚未完善个人信息</div>
                        <form role="form">
                            <div class="form-group">
                                <label>选择招募类型</label>
                                <div>
                                    @foreach (config('mcm.recruit_tags') as $item)
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="tags" value="{{ $item }}"> {{ $item }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label>当前队员</label>
                                <select name="team_id"></select>
                            </div>
                            <div class="form-group">
                                <label for="name">队伍描述</label>
                                <textarea name="description" class="form-control" maxlength="48" placeholder="请添加您的队伍描述，不超过48个字"></textarea>
                            </div>
                            <div class="form-group">
                                <label>联系方式</label>
                                <input type="text" name="contact" class="form-control" placeholder="请留下您的联系方式">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn">发布招募</button>
                                <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
