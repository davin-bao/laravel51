<?php

namespace App\Modules\Common\Middleware;

use App\Exceptions\BusinessException;
use App\Models\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class CatchExceptions
 * @package App\Modules\Common\Middleware
 *
 * 错误提示处理
 * 600-699 属于 HTML 错误
 * 700-799 属于 JSON 错误
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class CatchExceptions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch(BusinessException $e){
            $code = intval($e->getCode());
            if($code > 599 && $code <=699) {   //返回 HTML 响应
                self::redirectBack($e);
            }elseif($code > 699 && $code<=799){    //返回 JSON 响应
                self::renderJsonResult($e);
            }
        }
    }

    /**
     * 输出异常信息， 跳转回 GET 页
     *
     * @param BusinessException $exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function redirectBack(BusinessException $exception){
        \Toastr::error($exception->getMessage(), 'ERROR '.$exception->getCode());
        return redirect()->back();
    }

    /**
     * 输出 JSON 字符串
     * @param BusinessException $exception
     */
    public static function renderJsonResult(BusinessException $exception){
        header("Content-type:text/json;charset=UTF-8");
        $result['status'] = $exception->getCode();
        $result['msg']    = $exception->getMessage();
        $result['data'] = $exception->getData();
        die(json_encode($result));
    }
}
