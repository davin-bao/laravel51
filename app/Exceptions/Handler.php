<?php

namespace App\Exceptions;

use App\Components\MailHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/**
 * 异常定义与处理
 * Class Handler
 * @package App\Exceptions
 * @author: davin.bao
 * @since: 2016/7/15
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * 异常处理
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if($e instanceof  NotFoundHttpException){
            //无需处理
        }elseif($e instanceof AccessDeniedHttpException){
            //无需处理
        }elseif($e instanceof TooManyRequestsHttpException){
            //无需处理, 将返回自定义的 错误信息
        }elseif($e instanceof NoticeMessageException){
            //显示到终端，无需写日志，无需通知管理员
        }elseif($e instanceof ErrorMessageException){
            //显示到终端，需写日志，无需通知管理员
            \Log::error($e->getCode().PHP_EOL.$e->getMessage().PHP_EOL.$e->getTraceAsString());
        }elseif($e instanceof InterruptMessageException){
            //显示到终端，需写日志，需通知管理员
            \Log::error($e->getCode().PHP_EOL.$e->getMessage().PHP_EOL.$e->getTraceAsString());
            MailHelper::noticeTechSupport($e);
        }elseif($e instanceof \ErrorException){  //运行时异常，需写日志， 需通知管理员
            \Log::error($e->getCode().PHP_EOL.$e->getMessage().PHP_EOL.$e->getTraceAsString());
            MailHelper::noticeTechSupport($e);
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }
}
