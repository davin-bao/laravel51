<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="/index" id="logo" class="navbar-brand">
            <img src="{!! URL::asset('centaurus/img/logo.png') !!}" alt="" class="normal-logo logo-white">
            <img src="{!! URL::asset('centaurus/img/logo-black.png') !!}" alt="" class="normal-logo logo-black">
            <img src="{!! URL::asset('centaurus/img/logo-small.png') !!}" alt="" class="small-logo hidden-xs hidden-sm hidden">
        </a>
        <div class="clearfix">
            <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                <ul class="nav navbar-nav pull-left">
                    <li>
                        <a class="btn" id="make-small-nav">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-no-collapse pull-right" id="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown hidden-xs">
                        <a class="btn dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-warning"></i>
                            <span class="count">8</span>
                        </a>
                        <ul class="dropdown-menu notifications-list">
                            <li class="pointer">
                                <div class="pointer-inner">
                                    <div class="arrow"></div>
                                </div>
                            </li>
                            <li class="item-header">You have 6 new notifications</li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-comment"></i>
                                    <span class="content">New comment on ‘Awesome P...</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-plus"></i>
                                    <span class="content">New user registration</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-envelope"></i>
                                    <span class="content">New Message from George</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="content">New purchase</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-eye"></i>
                                    <span class="content">New order</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item-footer">
                                <a href="#">
                                    View all notifications
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{!! Html::avatar(35) !!}" alt="">
                            <span class="hidden-xs">{!! Html::getStaff()->name !!}</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ adminAction('DashboardController@getProfile') }}"><i class="fa fa-user"></i>Profile</a></li>
                            <li><a href="{{ adminAction('IndexController@getLogout') }}"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </li>
                    <li class="hidden-xxs">
                        <a href="{{ adminAction('IndexController@getLogout') }}" class="btn" >
                            <i class="fa fa-power-off"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>