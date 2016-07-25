<?php

namespace App\Modules\Common\Middleware;

use App\Exceptions\BusinessException;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        } catch(BusinessException $exception){
            //业务逻辑错误
            $errors = ['msg'=>$exception->getMessage(), 'code'=>$exception->getCode()];

            if ($request->ajax() || $request->wantsJson()) { //输出 JSON 字符串
                return new JsonResponse($errors, $exception->getCode());
            }

            //输出异常信息， 跳转回 GET 页
            \Html::error($exception->getMessage(), $exception->getCode());

            return redirect()->back()
                ->withInput($request->input())
                ->withErrors($errors, $exception->getMessage());
        } catch(HttpResponseException $exception){
            //输出异常信息， 跳转回 GET 页
            \Html::error('输入参数有误', 422);

            //表单验证错误
            throw $exception;
        }
    }

}
