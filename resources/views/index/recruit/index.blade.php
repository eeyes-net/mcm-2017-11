@extends('index.layouts.master')

@section('main')
    <div class="mcm-recruit">
        <div class="row options">
            <div class="col-md-6">
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown">
                        全部 <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">全部</a>
                        </li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">招募代码</a>
                        </li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">招募算法</a>
                        </li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">招募文书</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 text-md-right">
                <button type="button" class="btn btn-default sponsored" data-toggle="modal" data-target="#sign">发布招募</button>
            </div>
        </div>

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

        <div class="modal fade" id="sign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="developer"> 招募代码
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="algorithm"> 招募算法
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="writer"> 招募文书
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>当前队员</label>
                                <textarea class="form-control" maxlength="48" placeholder="请留下您的队伍中当前队员信息"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name">队伍描述</label>
                                <textarea class="form-control" maxlength="48" placeholder="请添加您的队伍描述，不超过48个字"></textarea>
                            </div>
                            <div class="form-group">
                                <label>联系方式</label>
                                <input type="text" class="form-control" placeholder="请留下您的联系方式">
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
