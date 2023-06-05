@php
	use App\Models\State;
	use App\Models\District;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL').'/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header">{{ $title }}</div>
				<div class="card-body bg-light">
					{{ Form::open(['method' => 'post', 'id' => 'new-city-form', 'autocomplete' => 'off']) }}
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('cit_name', $city::attributeLabel('cit_name'), ['for' => 'cit_name','class' => 'required']) }}
								{{ Form::text('cit_name', $city->cit_name, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('cit_sta_id', $city::attributeLabel('cit_sta_id'), ['for' => 'cit_sta_id','class' => 'required']) }}
								{{ Form::select('cit_sta_id', [null => '--Select--'] + State::ListData(), $city->cit_sta_id, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('cit_dis_id', $city::attributeLabel('cit_dis_id'), ['for' => 'cit_dis_id','class' => 'required']) }}
								{{ Form::select('cit_dis_id', [null => '--Select--'] + District::ListData(), $city->cit_dis_id, ['class' => 'select2 form-control']) }}
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('cit_status', $city::attributeLabel('cit_status'), ['for' => 'cit_status','class' => 'required']) }}
								{{ Form::select('cit_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $city->cit_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $city::attributeLabel('cit_status')]) }}
							</div>
						</div>
						<div class="">
							<a href="{{url(env('ADMIN_BASE_URL').'/city')}}" class="btn btn-info">Back</a>
							<button type="button" id="newstate" class="btn btn-success">Save</button>
						</div>
						{{ Form::close() }}
					</div>
				</div>

				<script>
					$(document).ready(function() {
						$('.select2').select2();
						// $('.select3').select2();
						$('#newstate').click(function(e) {
							e.preventDefault();
							$.ajax({
								type: "POST",
								url: "<?= env('ADMIN_BASE_URL')?>/city",
								data: $('#new-city-form').serialize(),
								cache: false,
								success: function(response) {
									handleResponses(response, 'new-city-form');
								},
								error: function(data) {
									handleResponses(data, 'new-city-form');
								}
							});
						});
					});
				</script>
			</div>
		</div>
	</section>
@endsection
