<?php namespace App\Components\Html;

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
        "Open_Sans400-600-700-00Titillium-Web200-300-400" => "centaurus/fonts/Open_Sans400-600-700-00Titillium-Web200-300-400.css"
    ];

    private $footerScriptList = [
        "demo-skin-changer" => "centaurus/js/demo-skin-changer.js",
        "bootstrap-script" => "centaurus/js/bootstrap.js",
        "jquery.nanoscroller" => "centaurus/js/jquery.nanoscroller.min.js",
        "demo" => "centaurus/js/demo.js",
        "jquery-script" => "centaurus/js/scripts.js"
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
     * 过滤掉子菜单中的非menu的菜单
     * @param $permissions
     * @return array|null
     */
    protected static function filterSubMenu($permissions) {
        if($permissions) {
            foreach ($permissions as $permission) {
                $subMenus = [];
                if($permission->sub_permission) {
                    foreach ($permission->sub_permission as $sub) {
                        if($sub->is_menu) {
                            $subMenus[] = $sub;
                        }
                    }
                    $permission->sub = $subMenus;
                    unset($permission->sub_permission);
                }

                $menus[] = $permission;
            }
            return $menus;
        }
        return null;
    }

    /**
     * 得到当前登录用户权限内的菜单栏
     * @return string
     */
    public function getMenu() {
        $permissions = Auth::staff()->get()->getPermissions();
        $menus = self::filterSubMenu($permissions);

        $html = '<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                    <ul class="nav nav-pills nav-stacked">';
        if ($menus) {
            $currentRouteName = json_decode(Route::currentRouteName());
            $routeDisplayName = $currentRouteName->display_name;
            foreach ($menus as $menu) {
                if ($menu->display_name === $routeDisplayName) {
                    $active = ' class="active"';
                } else {
                    $active = '';
                }
                $icon = $menu->icon;
                $display_name = $menu->display_name;
                if (!$menu->sub) {
                    $aTagHref = $menu->uri;
                    $aTagClass = '';
                    $menuRightIcon = "<span class=\"label label-info label-circle pull-right\">Jump</span>";
                    $subMenu = '';
                } else {
                    $aTagHref = '#';
                    $aTagClass = " class=\"dropdown-toggle\"";
                    $menuRightIcon = "<i class=\"fa fa-chevron-circle-right drop-icon\"></i>";
                    $subMenu = "<ul class=\"submenu\">";
                    foreach ($menu->sub as $sub) {
                        $subHref = $sub->uri;
                        $subDisplayName = $sub->display_name;
                        $subMenu .= "<li>
                                        <a href=\"$subHref\">
                                            $subDisplayName
                                        </a>
                                    </li>";
                    }
                    $subMenu .= '</ul>';
                }
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
        $html .= '</ul>
               </div>';
        return $html;
    }
}