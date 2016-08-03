@extends('Admin::vendor.index')
@section('container')
    @section('stylesheets')
        <link rel="stylesheet" href="{{ asset('centaurus/css/libs/select2.css') }}" type="text/css"/>
    @endsection
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    {!! Breadcrumbs::render("admin-staff-profile") !!}
                    <h1>User Profile</h1>
                </div>
            </div>
            <div class="row" id="user-profile">
                <div class="col-lg-3 col-md-4 col-sm-4">
                    <div class="main-box clearfix">
                        <header class="main-box-header clearfix">
                            <h2>{{ $staff->username }}</h2>
                        </header>
                        <div class="main-box-body clearfix">
                            <img src="{!! Html::avatar(159) !!}" alt="" role="button" title="设置头像" class="profile-img img-responsive center-block" id="update-avatar"/>
                            <div class="profile-label">
                                <span class="label label-danger">{{ $staff->name }}</span>
                            </div><br>
                            <div class="profile-since">
                                最近活动时间： {{ $staff->lastSeen() }}
                            </div>
                            <div class="profile-details">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-8">
                    <div class="main-box clearfix">
                        <div class="tabs-wrapper profile-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-newsfeed" data-toggle="tab">个人资料</a></li>
                                <li><a href="#tab-activity" data-toggle="tab">Activity</a></li>
                                <li><a href="#tab-friends" data-toggle="tab">Friends</a></li>
                                <li><a href="#tab-chat" data-toggle="tab">Chat</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab-newsfeed">
                                    <div class="main-box-body clearfix">
                                        <div class="row">
                                            <div class="row-offset">&nbsp;</div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-offset-1 col-md-6 col-lg-6 avatar-group" id="set-avatar-container">
                                            </div>
                                        </div>
                                        <form id="save-form">
                                            <div class="row">
                                                <div class="form-group col-lg-offset-1 col-md-6 col-lg-6 name-group">
                                                    <label for="name">邮箱</label>
                                                    <input type="text" class="form-control valid" id="email"
                                                           name="email"
                                                           placeholder="" aria-required="true" aria-invalid="false">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-offset-1 col-md-10 col-lg-10 name-group">
                                                    <label for="name">用户名</label>
                                                    <input type="text" class="form-control valid" id="name" name="name"
                                                           placeholder="" aria-required="true" aria-invalid="false">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-offset-1 col-md-10 col-lg-10 name-group">
                                                    <label for="name">手机号码</label>
                                                    <input type="text" class="form-control valid" id="mobile"
                                                           name="mobile"
                                                           placeholder="" aria-required="true" aria-invalid="false">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-offset-1 col-md-10 col-lg-10 timezone-group">
                                                    <label for="name">时区</label>
                                                    <div class="form-group form-group-select2">
                                                        <select style="width:300px" id="sel2" tabindex="-1"
                                                                class="select2-offscreen">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-10 tool-bar"></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <input class="hidden" id="upload-url" value="{{ adminAction('StaticController@postUploadAvatar') }}">
                                    <input class="hidden" id="dz-style" value="{{ asset('dropzone/css/dropzone.min.css') }}">
                                    <input class="hidden" id="dz-js" value="{{ asset('dropzone/js/dropzone.min.js') }}">
                                    <input class="hidden" id="dz-config" value="{{ asset('dropzone/js/dropzone-config.js') }}">
                                    <input class="hidden" id="user-id" value="{{ $staff->id }}">
                                </div>
                                <div class="tab-pane fade" id="tab-activity">
                                </div>
                                <div class="tab-pane clearfix fade" id="tab-friends">
                                </div>
                                <div class="tab-pane fade" id="tab-chat">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot-scripts')
    @parent
    <script src="{{ asset('centaurus/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/common/selector.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin/staff_profile.js') }}"></script>
@endsection