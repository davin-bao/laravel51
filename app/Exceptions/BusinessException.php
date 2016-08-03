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
     * BusinessException constructor.
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     * @param array $data
     */
    public function __construct( $message = null,$code = 500, \Exception $previous = null, $data = [])
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
