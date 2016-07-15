<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * BusinessException.
 *
 * 业务异常基类
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
abstract class BusinessException extends HttpException
{
    /**
     * @var array 携带一定的业务数据
     */
    public $data = array();
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 500, $data = [])
    {
        $this->data = $data;
        parent::__construct($code, $message, $previous, array(), $code);
    }

    /**
     * 获取业务数据
     * @return array
     */
    public function getData(){
        return $this->data;
    }
}
