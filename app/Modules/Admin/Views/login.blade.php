@extends('vendor.master')
@section('head')
    {!! Html::headerLink() !!}
@endsection

@section('title', 'Login')

@section('body')

<div id="login-full-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="login-box">
                    <div id="login-box-holder">
                        <div class="row">
                            <div class="col-xs-12">
                                <header id="login-header">
                                    <div id="login-logo">
                                        <img src="{!! URL::asset('centaurus/img/logo.png') !!}" alt=""/>
                                    </div>
                                </header>
                                <ul id="login-box-inner">
                                    <form id="login-form">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input class="form-control" type="text" placeholder="Email address" name="username" id="username" value="{{ Input::old('username') }}">
                                        </div>
                                        <label id="username-error" class="has-error"></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                                        </div>
                                        <label id="password-error" class="has-error"></label>
                                        <div id="remember-me-wrapper">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="checkbox-nice">
                                                        <input type="checkbox" id="remember-me" name="remember-me" checked="checked"/>
                                                        <label for="remember-me">
                                                            Remember me
                                                        </label>
                                                    </div>
                                                </div>
                                                <a href="forgot-password-full.html" id="login-forget-link" class="col-xs-6">
                                                    Forgot password?
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 tool-bar">
                                                <a class="btn btn-success col-xs-12" id="login-btn">Login</a>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled row error-list">
                                        </ul>
                                        <div class="row hidden">
                                            <div class="col-xs-12">
                                                <p class="social-text">Or login with</p>
                                            </div>
                                        </div>
                                        <div class="row hidden">
                                            <div class="col-xs-12 col-sm-6">
                                                <button type="submit" class="btn btn-primary col-xs-12 btn-facebook">
                                                    <i class="fa fa-facebook"></i> facebook
                                                </button>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <input type="hidden" class="form-control" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="btn btn-primary col-xs-12 btn-twitter">
                                                    <i class="fa fa-twitter"></i> Twitter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="login-box-footer">
                        <div class="row">
                            <div class="col-xs-12">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Admin::vendor.layout_option')
{!! Html::footerScript() !!}

<script type="text/javascript" src="{{ asset('js/admin/login.js') }}"></script>

@endsection
