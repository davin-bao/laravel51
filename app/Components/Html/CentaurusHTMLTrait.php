<?php
namespace App\Components\Html;

use App\Models\Permission;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Form 自定义组件
 * Class CentaurusFormTrait
 * @package App\Components\Html
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
trait CentaurusHtmlTrait {

    private $styleList = [
        "bootstrap" => "centaurus/css/bootstrap/bootstrap.min.css",
        "font-awesome" => "centaurus/css/libs/font-awesome.css",
        "nanoscroller" => "centaurus/css/libs/nanoscroller.css",
        "theme_styles" => "centaurus/css/libs/theme_styles.css",
        "ns-default" => "centaurus/css/libs/ns-default.css",
        "ns-style-attached" => "centaurus/css/libs/ns-style-attached.css",
        "ns-style-bar" => "centaurus/css/libs/ns-style-bar.css",
        "ns-style-growl" => "centaurus/css/libs/ns-style-growl.css",
        "ns-style-other" => "centaurus/css/libs/ns-style-other.css",
        "ns-style-theme" => "centaurus/css/libs/ns-style-theme.css",
        "bootstrap-dialog" => "centaurus/css/bootstrap/bootstrap-dialog.min.css",
//        "bootstrap-override" => "centaurus/css/bootstrap/bootstrap-override.css",
        "Open_Sans400-600-700-00Titillium-Web200-300-400" => "centaurus/fonts/Open_Sans400-600-700-00Titillium-Web200-300-400.css"
    ];

    private $footerScriptList = [
        "demo-skin-changer" => "centaurus/js/demo-skin-changer.js",
        "bootstrap-script" => "centaurus/js/bootstrap.js",
        "jquery.nanoscroller" => "centaurus/js/jquery.nanoscroller.min.js",
        "demo" => "centaurus/js/demo.js",
        "jquery-script" => "centaurus/js/scripts.js",
        "classie" => "centaurus/js/classie.js",
        "modernizr-custom" => "centaurus/js/modernizr.custom.js",
        "notificationFx" => "centaurus/js/notificationFx.js",
        "bootstrap-dialog" => "centaurus/js/bootstrap-dialog.min.js",
        "jquery-maskedinput" => "centaurus/js/jquery.maskedinput.min.js",
        "jquery-validate" => "js/common/jquery.validate.min.js",
        "jquery-validator" => "js/common/validator.js",
        "cache-script" => "js/common/cache.js",
        "public-script" => "js/common/public.js",
        "widgets-script" => "js/common/widgets.js",
    ];

    public function headerLink(){

        $html = '<!-- start: CSS -->
        ';
        //load all css file
        foreach($this->styleList as $key => $value){
            $html .= \Html::style( \URL::asset($value), ['id' => $key]);
        }
        $html .= '
        <!-- end: CSS -->';

        return $html . '
            <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
            <!--[if lt IE 9]>
                ' . \Html::script( \URL::asset("centaurus/js/html5shiv.js"), ['id' => 'html5-js']) .'
                ' . \Html::script( \URL::asset("centaurus/js/respond.min.js"), ['id' => 'respond-js']) .'
                ' . \Html::style( \URL::asset("centaurus/css/libs/ie.css"), ['id' => 'ie-style']) .'
            <![endif]-->

            <!--[if IE 9]>
                ' . \Html::style( \URL::asset("centaurus/css/libs/ie9.css"), ['id' => 'ie9-style']) .'
            <![endif]-->
            ' . \Html::script( \URL::asset("centaurus/js/jquery.js"), ['id' => 'jquery-script']) .'
            ';
    }

    public function footerScript(){
        $html = '<!-- start: Foot Script -->
        ';
        //load all css file
        foreach($this->footerScriptList as $key => $value){
            $html .= \Html::script( \URL::asset($value), ['id' => $key]);
        }
        $html .= '
        <!-- end: Foot Script -->';

        return $html;
    }

    public function notification(){

        $notifications = $this->session->get('centaurus:notifications');

        if(!$notifications) {
            return '';
        }

        foreach ($notifications as $notification) {
            $javascript = '';

            $message = str_replace("'", "\\'", $notification['message']);
            $type = $notification['type'];
            $javascript .= "
            <script type=\"text/javascript\">
             var notification = new NotificationFx({
					message : '<p>$message</p>',
					layout : 'growl',
					effect : 'genie',
					type : '$type', // notice, warning or error
					onClose : function() {
						//关闭
					}
				});

				notification.show();
			</script>
			";
        }

        echo $javascript;
    }

    /**
     * 添加 info 提示
     * @param $message
     * @param string $code
     */
    public function info($message, $code = ''){
        $this->add('notice', 'NOTICE '. $code . ': '.$message);
    }

    /**
     * 添加 success 提示
     * @param $message
     * @param string $code
     */
    public function success($message, $code = ''){
        $this->add('success', 'CODE '. $code . ': '.$message);
    }

    /**
     * 添加 warning 提示
     * @param $message
     * @param string $code
     */
    public function warning($message, $code = ''){
        $this->add('warning', 'WARNING '. $code . ': '.$message);
    }

    /**
     * 添加 error 提示
     * @param $message
     * @param string $code
     */
    public function error($message, $code = ''){
        $this->add('error', 'CODE '. $code . ': '.$message);
    }

    private function add($type, $message)
    {
        $types = ['notice', 'warning', 'success', 'error'];
        if(!in_array($type, $types)) {
            return false;
        }

        $this->notifications[] = [
            'type' => $type,
            'message' => htmlspecialchars($message)
        ];
        $this->session->flash('centaurus:notifications', $this->notifications);
    }

    /**
     * 得到当前登录用户的信息
     * @return mixed
     */
    public function getStaff(){
        return Auth::staff()->get();
    }
    /**
     * 得到当前登录用户的头像
     * @return string
     * @author  cunqinghuang
     * @since  2016-07-25 17:00
     */
    public function avatar( $s = 80, $d = 'mm', $r = 'g'){
        if($this->getStaff()->avatar){
            return asset($this->getStaff()->avatar);
        }else{
            $email = $this->getStaff()->email;
            $avatar =\Config::get('app.avatar_host').md5($email)."?s=$s&d=$d&r=$r";
            return $avatar;
        }
    }

    /**
     * 得到当前登录用户权限内的菜单栏
     * @return string
     *
     * @author chuanhangyu
     * @since 2016/7/21 15:30
     */
    public function getMenu() {
        $permissions = Auth::staff()->get()->getPermissions();
        $menus = [];

        // 循环得到所有顶级菜单下的子菜单
        foreach($permissions as $permission){
            if(empty($permission->parent) && $permission->is_menu === Permission::IS_MENU_YES){
                $subPermissions = [];
                foreach($permissions as $subPermission) {
                    if ($subPermission->parent && $subPermission->is_menu === Permission::IS_MENU_YES && preg_match('~'. $subPermission->parent. '~', $permission->action)) {
                        array_push($subPermissions, $subPermission);
                    }
                }
                $permission->sub = $subPermissions;
                array_push($menus, $permission);
            }
        }

        // 将所有菜单包裹在HTML中

        // 菜单栏样式主题主体，不管该用户有没有权限菜单
        $html = '<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                    <ul class="nav nav-pills nav-stacked">';

        // 得到当前路由信息
        $routeActionName = Route::current()->getActionName();

        // 当该用户有对应权限的菜单时
        if ($menus && $routeActionName) {

            // 循环包裹菜单
            // 循环包裹顶级菜单
            foreach ($menus as $menu) {

                // 为当前路由对应的菜单配置激活class
                if ($menu->action === $routeActionName) {
                    $active = ' class="active"';
                } else {
                    $active = '';
                }
                $icon = $menu->icon;
                $display_name = $menu->display_name;

                // 如果此菜单无子菜单，设置其对应的右图标
                if (!$menu->sub) {
                    $aTagHref = action('\\'.$menu->action);
                    $aTagClass = '';
                    $menuRightIcon = "<span class=\"label label-info label-circle pull-right\">Jump</span>";
                    $subMenu = '';
                } else {

                    // 如果此菜单有子菜单，为其设置右下拉图标，并为其配置子菜单
                    $aTagHref = '#';
                    $aTagClass = " class=\"dropdown-toggle\"";
                    $menuRightIcon = "<i class=\"fa fa-chevron-circle-right drop-icon\"></i>";
                    $subMenu = "<ul class=\"submenu\">";

                    // 循环配置子菜单
                    foreach ($menu->sub as $sub) {
                        if ($sub->action === $routeActionName) {
                            $subActive = ' class="active"';
                            $active = ' class="active open"';
                        } else {
                            $subActive = '';
                        }
                        $subHref = action('\\'.$sub->action);
                        $subIcon = $sub->icon;
                        if ($subIcon) {
                            $subIconTag = "<i class=\"fa fa-$subIcon\" style=\"margin-right: 0.5em\"></i>";
                        } else {
                            $subIconTag = "<i style=\"margin-right: 1.5em\"></i>";
                        }
                        $subDisplayName = $sub->display_name;
                        $subMenu .= "<li>
                                        <a href=\"$subHref\"$subActive>
                                            $subIconTag
                                            $subDisplayName
                                        </a>
                                    </li>";
                    }
                    $subMenu .= '</ul>';
                }
                // 拼接所有html
                $html .= "<li$active>
                            <a href=\"$aTagHref\"$aTagClass>
                                <i class=\"fa fa-$icon\"></i>
                                <span>$display_name</span>
                                $menuRightIcon
                            </a>
                            $subMenu
                          </li>";
            }
        }

        // 闭合主体
        $html .= '</ul>
               </div>';
        return $html;
    }
}
