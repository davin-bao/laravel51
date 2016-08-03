<?php

namespace App\Modules\Common;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 控制器基类
 * Class Controller
 * @package App\Modules\Common
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
abstract class Controller extends BaseController {

    use DispatchesCommands,
        ValidatesRequests;

    /**
     * @inheritdoc
     */
    public function __construct() {
        $this->middleware('last_activity');
        $this->middleware('catch_exceptions');
    }

    /**
     * Service 实例
     * 必须每个Controller 都要指定Service
     * @var null
     */
    private $service = null;

    /**
     * 获取 Service 实例
     * @return null
     */
    protected function getService(){
        if(!$this->service){
            $serviceName = str_replace('Controller', 'Service', get_called_class()).'';
            $this->service = new $serviceName();
        }
        return $this->service;
    }

    /**
     * 验证表单合法性
     * @param array $rules
     * @param Request $request
     */
    protected function validateRequest(array $rules, Request $request, $customAttributes=[]){
        $validator = Validator::make($request->all(), $rules, [], $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
    }

    /**
     * Missing method handler
     * 
     * @param array $parameters
     * @return \Illuminate\View\View
     */
    public function missingMethod($parameters = [])
    {
        return view('errors.404', ['admin' => null]);
    }

    /**
     * 设置可配置模块权限的路由
     */
    public function getModule(){}

    /**
     *
     * return result
     * @author: davin.bao
     * @since: 2016/3/8
     *
     * @param Request $request 请求参数
     * @param array $data  数据
     * @param string $nextUrl   跳转地址
     *
     * @return $this|JsonResponse
     */
    protected function response(Request $request, array $data,  $nextUrl = null){

        $message = isset($data['msg']) ? $data['msg'] : '操作成功';

        if ($request->ajax() || $request->wantsJson()) {
            return new JsonResponse(array_merge($data, ['msg'=>$message, 'next_url'=>$nextUrl, 'code'=>200]), 200, $headers = [], 0);
        }

        \Html::success($message, 200);

        return redirect()->to($nextUrl);
    }

    /**
     * 为列表生成结果
     * @param Request $request
     * @param $query
     * @param string $countColumns
     * @return array
     */
    protected function queryData(Request $request, $query, $countColumns = '*'){
        //起始记录
        $start = intval($request->input('start',0));
        //每页数量
        $length = intval($request->input('length',100));
        $sortOrder = $request->input('order', [0=>['dir'=>'DESC']])[0]['dir'];
        $sortColumnId = $request->input('order', [0=>['column'=>'id']])[0]['column'];
        $columns = $request->input('columns',null);
        if(is_array($columns) && isset($columns[$sortColumnId]) && isset($columns[$sortColumnId]['data'])){
            $sortName = $columns[$sortColumnId]['data'];
        }

        $sortName = empty($sortName) ? 'id' : $sortName;
        // Order
        $query = $query->orderBy($query->getModel()->getTable().'.'.$sortName, $sortOrder);

        $totalCount = $query->count($countColumns);
        $results = $query->skip($start)->take($length)->get()->toArray();

        $data = [
            'data' => $results,
            'start' => $start,
            'length' => $length,
            'recordsTotal' => $totalCount
        ];

        return $data;
    }

    /**
     * Creates params array
     *
     * @param Request|\Illuminate\Http\Request $request
     * @return Array
     */
    protected function formParams(Request $request) {
        $params = [];

        $params['search'] = $request->input('search') ? $request->input('search') : null;
        $params['limit'] = $request->input('limit') ? $request->input('limit') : null;
        $params['order'] = $request->input('order') ? $request->input('order') : null;
        $params['param'] = $request->input('param') ? $request->input('param') : null;

        return $params;
    }

    /**
     * 导出excel
     * @param $view
     * @param $name
     * @param $query
     * @param $otherData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excel($view, $name, $query, $otherData) {
        $fileName = $name . '_' . date('YmdHis');

        header("Content-type:text/xls;charset=UTF-8");
        header("Content-Disposition:attachment;filename=$fileName.xls");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        header("Content-Transfer-Encoding: binary ");

        return $this->render($view, array_merge($this->queryDataForExport($query), $otherData));
    }

    /**
     * 导出word
     * @param $view
     * @param $name
     * @param $query
     * @param $otherData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function word($view, $name, $query, $otherData) {
        $fileName = $name . '_' . date('YmdHis');

        header("Content-type:text/doc");
        header("Content-Disposition:attachment;filename=$fileName.doc");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        return $this->render($view, array_merge($this->queryDataForExport($query), $otherData));
    }

    /**
     * 获取检索的结果
     * @param \Illuminate\Http\Request $query
     * @return array
     */
    protected function queryDataForExport($query){
        $page = 0;
        $limit = 5000;
        $sortOrder = 'asc';
        $sortName = 'id';

        $query = $query->orderBy($query->getModel()->getTable().'.'.$sortName, $sortOrder)->skip($limit * ($page-1))->take($limit)->get()->toArray();

        $totalCount = count($query);
        $data = [
            'rows' => $query,
            'records' => $totalCount
        ];

        return $data;
    }
}
