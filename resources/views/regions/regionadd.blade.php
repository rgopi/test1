@php
	use App\Models\State;
	use App\Models\District;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL') . '/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header">{{ $title }}</div>
				<div class="card-body bg-light">
					{{ Form::open(['method' => 'post', 'id' => 'new-region-form', 'autocomplete' => 'off']) }}
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('reg_name', $region::attributeLabel('reg_name'), ['for' => 'reg_name', 'class' => 'required']) }}
								{{ Form::text('reg_name', $region->reg_name, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('reg_sta_ids', $region::attributeLabel('reg_sta_ids'), ['for' => 'reg_sta_ids', 'class' => 'required']) }}
								<div class="select2-success">
									{{ Form::select('reg_sta_ids[]', State::ListData(), $region->reg_sta_ids, ['class' => 'form-control select2', 'data-placeholder' => ' Select ' . $region::attributeLabel('reg_sta_ids'), 'multiple' => 'multiple', 'id' => 'reg_sta_ids']) }}
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('reg_status', $region::attributeLabel('reg_status'), ['for' => 'reg_status', 'class' => 'required']) }}
								{{ Form::select('reg_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $region->reg_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $region::attributeLabel('reg_status')]) }}
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer bg-light">
					<a href="{{ url(env('ADMIN_BASE_URL') . '/regions') }}" class="btn btn-info text-light">Back</a>
					<button type="button" id="newstate" class="btn btn-success">Save</button>
				</div>
				{{ Form::close() }}

				<script>
					$(document).ready(function() {
						$('.select2').select2();
						$('#newstate').click(function(e) {
							e.preventDefault();
							$.ajax({
								type: "POST",
								url: "<?= env('ADMIN_BASE_URL') ?>/regions",
								data: $('#new-region-form').serialize(),
								cache: false,
								success: function(response) {
									handleResponses(response, 'new-region-form');
								},
								error: function(data) {
									handleResponses(data, 'new-region-form');
								}
							});
						});
					});
				</script>
			</div>
		</div>
	</section>
@endsection
