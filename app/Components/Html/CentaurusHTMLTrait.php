<?php namespace App\Components\Html;

use App\Models\Permission;

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


}