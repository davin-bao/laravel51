@extends('Admin::vendor.master')
@section('head-scripts')
    @parent

    {!! \Html::style( \URL::asset('centaurus/css/libs/dataTables.fixedHeader.css'), ['id' => 'dataTables-fixedHeader']) !!}
    {!! \Html::style( \URL::asset('centaurus/css/libs/dataTables.tableTools.css'), ['id' => 'dataTables-tableTools']) !!}

    {!! \Html::script( \URL::asset('centaurus/js/jquery.dataTables.js'), ['id' => 'jquery-dataTables']) !!}
    {!! \Html::script( \URL::asset('centaurus/js/dataTables.fixedHeader.js'), ['id' => 'dataTables-fixedHeader']) !!}
    {!! \Html::script( \URL::asset('centaurus/js/dataTables.tableTools.js'), ['id' => 'dataTables-tableTools']) !!}
    {!! \Html::script( \URL::asset('centaurus/js/jquery.dataTables.bootstrap.js'), ['id' => 'jquery-dataTables-bootstrap']) !!}

@endsection

@section('foot-scripts')
    @parent

@endsection
