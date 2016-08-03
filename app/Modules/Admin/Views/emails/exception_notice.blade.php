@extends('Admin::emails.master')

@section('content')
<table>
    <tr>
        <th  align="right" valign="top" style="width: 100px;">发生时间: </th>
        <td valign="top">{{ date('Y-m-d h:i:s', time()) }}</td>
    </tr>
    <tr>
        <th align="right" valign="top">错误码: </th>
        <td valign="top">{{ $code }}</td>
    </tr>
    <tr>
        <th align="right" valign="top">错误内容: </th>
        <td valign="top">{{ $content }} </td>
    </tr>
    <tr>
        <th align="right" valign="top">Trace: </th>
        <td valign="top"><?php echo $trace ;?></td>
    </tr>
</table>
@stop