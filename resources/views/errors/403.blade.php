@extends('vendor.master')
@section('title')
	403 Access to this resource on the server is denied
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
							<img src="{{ asset('centaurus/img/error-404-v3.png') }}" alt="Forbidden 403"/>
						</div>
						<h1>Forbidden 403</h1>
						<p>
							你没有权限访问该网页<br/>

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
