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
trait CentaurusFormTrait {

    private $styleList = [
        "bootstrap" => "centaurus/css/bootstrap.min.css",
        "bootstrap-responsive" => "centaurus/css/bootstrap-responsive.min.css",
        "base-style" => "centaurus/css/style.css",
        "base-style-responsive" => "centaurus/css/style-responsive.css",
        "font-style" => "centaurus/css/font.css",
    ];

    private $footerScriptList = [
        "migrate-script" => "centaurus/js/jquery-migrate-1.0.0.min.js",
        "jquery-ui-script" => "centaurus/js/jquery-ui-1.10.0.custom.min.js",
        "jquery.ui.touch-script" => "centaurus/js/jquery.ui.touch-punch.js",
        "modernizr-script" => "centaurus/js/modernizr.js",
        "bootstrap-script" => "centaurus/js/bootstrap.min.js",
        "jquery-cookie-script" => "centaurus/js/jquery.cookie.js",
        "fullcalendar-script" => "centaurus/js/fullcalendar.min.js",
        "dataTables-script" => "centaurus/js/jquery.dataTables.min.js",
		"excanvas-script" => "centaurus/js/excanvas.js",
        "flot-script" => "centaurus/js/jquery.flot.js",
        "flot-pie-script" => "centaurus/js/jquery.flot.pie.js",
        "flot-stack-script" => "centaurus/js/jquery.flot.stack.js",
        "flot-resize-script" => "centaurus/js/jquery.flot.resize.min.js",
        "chosen-script" => "centaurus/js/jquery.chosen.min.js",
        "uniform-script" => "centaurus/js/jquery.uniform.min.js",
        "cleditor-script" => "centaurus/js/jquery.cleditor.min.js",
        "noty-script" => "centaurus/js/jquery.noty.js",
        "elfinder-script" => "centaurus/js/jquery.elfinder.min.js",
        "raty-script" => "centaurus/js/jquery.raty.min.js",
        "iphone-toggle-script" => "centaurus/js/jquery.iphone.toggle.js",
        "uploadify-script" => "centaurus/js/jquery.uploadify-3.1.min.js",
        "gritter-script" => "centaurus/js/jquery.gritter.min.js",
        "imagesloaded-script" => "centaurus/js/jquery.imagesloaded.js",
        "masonry-script" => "centaurus/js/jquery.masonry.min.js",
        "knob-modified-script" => "centaurus/js/jquery.knob.modified.js",
        "sparkline-script" => "centaurus/js/jquery.sparkline.min.js",
        "counter-script" => "centaurus/js/counter.js",
        "retina-script" => "centaurus/js/retina.js",
        "custom-script" => "centaurus/js/custom.js"
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
                ' . \Html::script( \URL::asset("js/common/html5.js"), ['id' => 'html5-js']) .'
                ' . \Html::style( \URL::asset("css/ie.css"), ['id' => 'ie-style']) .'
            <![endif]-->

            <!--[if IE 9]>
                ' . \Html::style( \URL::asset("css/ie9.css"), ['id' => 'ie9-style']) .'
            <![endif]-->
            ' . \Html::script( \URL::asset("centaurus/js/jquery-1.9.1.min.js"), ['id' => 'jquery-script']) .'
            ';
    }

    public function footScript(){
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

    public function operationButton($uri, $option, $icon, $text, $permissionUri = null){

        if(!Permission::can(is_null($permissionUri) ? $uri : $permissionUri)) return '';
        return sprintf(
            '<a href="%s" %s>
               <i class="%s"></i> %s
           </a>',
            \URL::to($uri),
            $option,
            $icon,
            $text
        );

        return $html;
    }

    public function submitButton($label, $icon, $option){
        return sprintf(
            '<button type="submit" name="submit" %s><i class="%s"></i> %s</button>',
            $option,
            $icon,
            $label
        );
    }

    public function textField($name, $value, $label, $errors, $inputOptions = array()){
        $inputOptions['class'] = 'form-control';
        $inputOptions['placeholder'] = $label;
        //get error field message
        $message = "";
        if(count($errors) > 0){
            $messageBag = current($errors->getBags());
            if(count($messageBag) > 0){
                $message = $messageBag->first($name);
            }
        }

        return sprintf(
            '<!-- begin %s form-group -->
            <div class="form-group span4 %s">
                <div class="input-group">
                <div class="input-group-addon"><label for="title" style="color: #ff0000; margin-left: 20px;">*</label><label><strong>%s</strong></label></div>
                <div class="input">%s</div>
                %s
                </div>
            </div>
            <!-- end %s form-group -->',
            $name,
            $message === "" ? "" : "error",
            $label,
            parent::text($name, $value, $inputOptions),
            $message === "" ? "" : '<span class="help-inline">'.$message.'</span>',
            $name
        );

    }

}