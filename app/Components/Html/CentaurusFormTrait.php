<?php
namespace App\Components\Html;

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