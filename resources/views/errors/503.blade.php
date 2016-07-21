@extends('vendor.master')
@section('title')
	503 Service Unavailable
@endsection

@section('head')
	<link rel="stylesheet" type="text/css" href="{{ asset('centaurus/css/libs/font-awesome.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('centaurus/css/libs/theme_styles.css') }}"/>
@endsection

@section('body')
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="error-box">
				<div class="row">
					<div class="col-xs-12">
						<div id="error-box-inner">
							<img src="{{ asset('centaurus/img/error-500-v1.png') }}" alt="Error 503"/>
						</div>
						<h1>HTTP ERROR 503</h1>
						<p>
							由于临时的服务器维护或者过载<br>
							服务器当前无法处理请求
						</p>
						<p>
							Go back to <a href="/">homepage</a>.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection