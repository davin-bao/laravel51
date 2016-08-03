## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### 登录API
    CURL Request GET:http://laravel5.list.local/login-api   {"username":"username","password":"password"}
请求参数：

名称 | 说明|
 ------------- | -------------
 username  | 用户账号，类型为字符串（必选参数）
 password  | 用户密码，类型为字符串（必选参数）
 redirect_url  | 登录后跳转界面，类型为字符串（可选参数，默认值为"http://51.laravel.com/admin"）
 remember_me  | 是否记住账号密码，类型为字布尔型（可选参数，默认值为false）

 返回值：

 名称 | 说明|
  ------------- | -------------
  Forbidden 403  | 无权限访问
  Error 404  | 该页面不存在
  Error 500  | 参数错误（账号密码错误）
  200 ok  | 登录成功



### 注册API
    CURL Request POST:http://laravel5.list.local/register-staff
    {"username":"username","password":"password","email":"email","name":"name","mobile":"mobile","roles":"roles"}
请求参数：

名称 | 说明|
 ------------- | -------------
 username  | 用户账号，类型为字符串（必选参数）
 password  | 用户密码，类型为字符串（必选参数）
 email  | 用户邮箱，类型字符串（必选参数）
 name  | 用户姓名，类型为字符串（必选参数）
 mobile  | 手机号码，类型为字符串（必选参数 ）
 roles  | 用户姓名，类型为字符串（必选参数 多个角色可以用逗号隔开标识）



  返回值：

  名称 | 说明|
   ------------- | -------------
   Forbidden 403  | 无权限访问
   Error 404  | 该页面不存在
   Error 500  |  参数错误
   200 ok  | 登录成功