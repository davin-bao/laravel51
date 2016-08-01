@extends('Admin::vendor.index')
@section('container')
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
                            <img src="{!! Html::avatar(159) !!}" alt="" class="profile-img img-responsive center-block"/>
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
                                <li class="active"><a href="#tab-newsfeed" data-toggle="tab">Newsfeed</a></li>
                                <li><a href="#tab-activity" data-toggle="tab">Activity</a></li>
                                <li><a href="#tab-friends" data-toggle="tab">Friends</a></li>
                                <li><a href="#tab-chat" data-toggle="tab">Chat</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab-newsfeed">
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
    <script type="text/javascript" src="{{ asset('js/admin/role_add.js') }}"></script>
@endsection