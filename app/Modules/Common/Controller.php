<?php

namespace App\Modules\Common;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

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

    protected function all(Request $request,
                           $query,
                           $uri,
                           $title,
                           $delete_message,
                           $view){
        return $this->render($view, $this->getQueryData($request, $query, $uri, $title, $delete_message));
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
     * return json result
     * @author: davin.bao
     * @since: 2016/3/8
     * @param string $queryData  查询条件
     * @param int $statusCode   返回code
     * @param string $message   返回提示信息
     */
    protected function renderJsonResult($queryData = null, $statusCode = 200, $message = 'success'){
        $result['status'] = $statusCode;
        $result['msg']    = $message;
        $result['data'] = $queryData;
        die(json_encode($result));
    }

    /**
     * get all items pages
     *
     * @param Request $request
     * @param mixed $query
     * @param string $uri
     * @param string $title
     * @param string $delete_message
     * @param string $view
     * @return array
     */
    protected function getQueryData(
        Request $request,
        $query,
        $uri,
        $title,
        $delete_message
    ) {
        $params = $this->formParams($request);

        $results = $query;

        // Order
        if ($params['param'] && $params['order']) {
            $results = $results->orderBy($params['param'], $params['order']);
        }

        $paginator = $results->paginate($params['limit']);
        $paginator->appends(['limit' => $params['limit'], 'param' => $params['param'], 'order' => $params['order']]);
        $paginator->setPath('all');

        $data = [
            'results' => $paginator,
            'uri' => $uri,
            'title' => $title,
            'delete_message' => $delete_message,
            'limit' => $params['limit'],
            'param' => $params['param'],
            'order' => $params['order'],
            'search' => $params['search']
        ];

        return $data;
    }

    protected function queryData(Request $request, $query, $countColumns = '*'){
        $page = intval($request->input('page',0));
        $limit = intval($request->input('rows',100));
        $sortOrder = $request->input('sord', 'DESC');
        $sortName = $request->input('sidx', 'id');

        $sortName = empty($sortName) ? 'id' : $sortName;
        // Order
        $query = $query->orderBy($query->getModel()->getTable().'.'.$sortName, $sortOrder);

        $totalCount = $query->count($countColumns);
        $results = $query->skip($limit * ($page-1))->take($limit)->get()->toArray();

        $data = [
            'rows' => $results,
            'limit' => $limit,
            'page' => $page,
            'records' => $totalCount,
            'total' => ceil($totalCount/$limit)
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
