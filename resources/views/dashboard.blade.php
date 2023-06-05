@php
	use App\Models\Helper;
@endphp
@extends('layouts.adminlte')
@section('content')
	<script src="/plugins/chart.js/Chart.min.js"></script>
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">{{$title}}</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item active"><a href="">Home</a></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content admin-dashboard dashboard">
		<div class="container-fluid">
		</div>
	</section>
	<script>
		
	</script>
@endsection
