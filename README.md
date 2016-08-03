This project uses the Laravel 5.1 framework. Actually this is starter Laravel 5.1 project. It contains user management system, including register, login, forgotten password, change password and other related functionalities. It also contains Basic admin panel and user roles implementation.
The project has also Access Control List implementation.
The project uses several modules:

1. [laracasts/flash](https://github.com/laracasts/flash) - module used for the flash message notifications
2. [fzaninotto/faker](https://github.com/fzaninotto/Faker) - used to generate dummy data
3. [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) - used for the debug bar in development mode
5. [dimsav/laravel-translatable](https://github.com/dimsav/laravel-translatable) - used to translate models. It is necessary if you plan to create multilanguage application.
6. [todstoychev/icr](https://github.com/todstoychev/icr) - image manipulation module based on Imagine library.
7. [todstoychev/table-sorter](https://github.com/todstoychev/table-sorter) - Table sorter plugin

The project contains also several frontend plugins:

1. [Bootstrap 3](http://getbootstrap.com/) - can be found in ```public/bs/```
2. [jQuery 1.11.1](https://jquery.com/) - can be found in ```public/js/jquery.js```
3. [Select2 3.5.2 with Bootstrap compitability](http://select2.github.io/select2/) - can be found in ```public/select2/```
4. [Lightbox 2.7.1](http://lokeshdhakar.com/projects/lightbox2/) - can be found in ```public/lightbox/```
5. [FontAwesome 4.3.0](http://fortawesome.github.io/Font-Awesome/) - can be found in ```public/fa/```

# Installation
First clone the project. Than run
    
    composer update
    
Depending on your OS this command may be in different format.

## Configuration
Than you can create your .env file as it is in [Laravel 5 documentation](http://laravel.com/docs/master) or can use this sample:
    ```
    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=FgpUweA3vKzOXqVshIVGhiTcps2CnMxA

    DB_HOST=localhost
    DB_DATABASE=laravel_base
    DB_USERNAME=root
    DB_PASSWORD=

    CACHE_DRIVER=file
    SESSION_DRIVER=file

    EMAIL_HOST=smtp.exmail.qq.com
    EMAIL_PORT=25
    EMAIL_ENCRYPTION=tls
    EMAIL_ADDRESS=test@test.com
    EMAIL_PASSWORD=*******
    EMAIL_NAME=admin
```
Put your database host, username and password. ```EMAIL_ADDRESS``` is the application mailing service address. ```EMAIL_PASSWORD``` is the password for the mailbox. I am using this way of configuration due to the mail.php config file commit. I do not want to distribute my email and password ;).

For more details about the .env file, check [Laravel's documentation](http://laravel.com/docs/master) or just Google about it. There is a plenty of info out there.

## Run the migrations
First create your database and set the proper driver in the ```config/database.php``` file.
Use the Laravel's artisan console with the common commands to run the migrations. First cd to the project directory and depending from your OS run 
    
    php artisan migrate
    
## Add some dummy data
This project has seeders which provide the initial and some dummy data necessary for the project to run.
Use: 
    
    php artisan db:seed
    
to run the migrations.

## Create Access Control List
To create the initial Access Control List data run:
    
    php artisan acl:update
    
To clear the data you can use:
    
    php artisan acl:clear
    
If you add new controllers to your project, the rules for them can be set while running the update command. This command will not touch your existing database entries.    

## Your first login
Use 'admin' as username and 'admin' as password to enter the application. The 'admin' account has an administrator role so you have access to all application futures.

## About the user management
There are 2 roles in this application - admin and user. Admin role can not be deleted or edited. All other roles can be edited. 
Users can be deleted if they do not have something that relates to them. If you have only one active admin user, he can not be deleted or deactivated.

## Settings (Admin panel)
There is a settings module in the admin panel. You can define your site name from here. This is the name that will be shown at the navigation tab in your browser. Also you can define the locales. Use standart 2 characters locale codes. The fallback locale is used as default if the user has no choose a language from the language menu.

## ACL (Permissions)
This is the section from where you can control your users access. Against every action in the project you will see a checkbox. The checkboxes are situated under the roles named columns. If checkbox is checked, the current role has access to this action.

## Some bugs need fix
1. E:\www\laravel5_demo\vendor\laravel\framework\src\Illuminate\Cache\FileStore.php row: 10
```
    class FileStore extends TaggableStore implements Store
```

2. E:\www\laravel5_demo\vendor\todstoychev\icr\src\Processor.php row: 139
```
    try {
        $filesystemAdapter->delete($path . $fileName);
    }catch (\League\Flysystem\FileNotFoundException $e){
        //ignore  FileNotFoundException
    }
```

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

    例如：{"msg":"\u7528\u6237\u540d\u6216\u5bc6\u7801\u9519\u8bef!","code":500}

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

    例如：{"msg":"\u7ba1\u7406\u5458\u767b\u5f55\u6210\u509f","next_url";"\/admin","code":200}
  名称 | 说明|
   ------------- | -------------
   Forbidden 403  | 无权限访问
   Error 404  | 该页面不存在
   Error 500  |  参数错误
   200 ok  | 登录成功