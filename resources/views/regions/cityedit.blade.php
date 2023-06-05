@php
	use App\Models\State;
	use App\Models\District;

@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content">
		<section class="content-header">
			<a href="{{ url(env('ADMIN_BASE_URL').'/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
		</section>
		<div class="container-fluid">
			<div class="card  text-dark mt-5  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header bg-success">{{ $title }}</div>
				<div class="card-body ">
					{{ Form::open(['id' => 'edit-city', 'autocomplete' => 'off']) }}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('cit_name', $city::attributeLabel('cit_name'), ['for' => 'cit_name','class' => 'required']) }}
								{{ Form::text('cit_name', $city->cit_name, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('cit_sta_id', $city::attributeLabel('cit_sta_id'), ['for' => 'cit_sta_id','class' => 'required']) }}
								{{ Form::select('cit_sta_id', [null => '--Select--'] + State::ListData(), $city->cit_sta_id, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('cit_dis_id', $city::attributeLabel('cit_dis_id'), ['for' => 'cit_dis_id', 'class' => 'required']) }}
									{{ Form::select('cit_dis_id',[null => '--Select--'] + District::ListData(), $city->cit_dis_id, ['class' => 'form-control select2', 'data-placeholder' => ' Select ' . $city::attributeLabel('cit_dis_id'), 'id' => 'cit_dis_id']) }}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('cit_status', $city::attributeLabel('cit_status'), ['for' => 'cit_status','class' => 'required']) }}
								{{ Form::select('cit_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $city->cit_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $city::attributeLabel('cit_status')]) }}
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
					<button type="button" id="updatecity" class="btn btn-success">Save</button>
				</div>
				{{ Form::close() }}
			</div>

			<script>
				$(document).ready(function() {

					$('.select2').select2();

					$('#updatecity').click(function(e) {
						e.preventDefault();
						$.ajax({
							type: "POST",
							url: "<?= env('ADMIN_BASE_URL')?>/edit-city/<?= $city->cit_id ?>",
							data: $('#edit-city').serialize(),
							cache: false,
							success: function(response) {
								handleResponses(response, 'edit-city');
							},
							error: function(data) {
								handleResponses(data, 'edit-city');
							}
						});
					});
				});
			</script>
		</div>
	</section>
@endsection
