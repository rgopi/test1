@php
	use App\Models\State;
	use App\Models\District;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content">
		<section class="content-header">
			<a href="{{ url(env('ADMIN_BASE_URL') . '/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
		</section>
		<div class="container-fluid">
			<div class="card  text-dark mt-5  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header bg-success">{{ $title }}</div>
				<div class="card-body ">
					{{ Form::open(['id' => 'edit-region', 'autocomplete' => 'off']) }}
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
								<div class="select2-primary">
									{{ Form::select('reg_sta_ids[]', [null => '--Select--'] + State::ListData(), $region->reg_sta_ids, ['class' => 'form-control select2', 'data-placeholder' => ' Select ' . $region::attributeLabel('reg_sta_ids'), 'multiple' => 'multiple', 'id' => 'reg_sta_ids']) }}
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
				<div class="card-footer">
					<a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
					<button type="button" id="updateregion" class="btn btn-success">Save</button>
				</div>
				{{ Form::close() }}
			</div>
			<script>
				$(document).ready(function() {

					$('.select2').select2();

					$('#updateregion').click(function(e) {
						e.preventDefault();
						$.ajax({
							type: "POST",
							url: "<?= env('ADMIN_BASE_URL') .'/edit-region/'. $region->reg_id ?>",
							data: $('#edit-region').serialize(),
							cache: false,
							success: function(response) {
								handleResponses(response, 'edit-region');
							},
							error: function(data) {
								handleResponses(data, 'edit-region');
							}
						});
					});
				});
			</script>
		</div>
	</section>
@endsection
