@php
	use App\Models\City;
	use App\Models\State;
	use App\Models\District;
	use App\Models\Util;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL').'/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<?php
	$searchFieldsCount = count(array_filter($city->getAttributes(), function($x) { return !empty($x); }));
	$show =  $searchFieldsCount ? 'show' : '';
	?>
	<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header">{{ $title }}<button class="btn btn-sm btn-warning text-white float-right" type="button" id="triffer" data-toggle="collapse" data-target="#collapseExample"
                    aria-expanded="false" aria-controls="collapseExample">
                    Filter
                </button></div>
				<div class="collapse <?= $show ?>" id="collapseExample">
				<div class="card-body bg-light">
					{{ Form::open(['url' => env('ADMIN_BASE_URL').'/city', 'method' => 'GET', 'id' => 'search-form', 'autocomplete' => 'off']) }}
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
								{{ Form::select('cit_sta_id', [null => '--Select--'] + State::ListData(), $city->cit_sta_id, ['class' => 'form-control states']) }}
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('cit_dis_id', $city::attributeLabel('cit_dis_id'), ['for' => 'cit_dis_id', 'class' => 'required']) }}
								{{ Form::select('cit_dis_id', [null => '--Select--'], $city->cit_dis_id, ['class' => 'form-control district', 'data-placeholder' => ' Select ' . $city::attributeLabel('cit_dis_id'), 'id' => 'cit_dis_id']) }}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('cit_status', $city::attributeLabel('cit_status'), ['for' => 'cit_status','class' => 'required']) }}
								{{ Form::select('cit_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $city->cit_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $city::attributeLabel('cit_status')]) }}
							</div>
						</div>
						<div class="col-md-12 mt-2 text-right">
							<div class="form-group">
								<?= \App\Models\Util::gridToolHtml(['new_url' => env('ADMIN_BASE_URL').'/cityadd', 'manage_url' => env('ADMIN_BASE_URL').'/city',]) ?>
							</div>
						</div>
					</div>
					{{ Form::close() }}
				</div>
			</div>
				<div class="card-body bg-light">
					<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>{{env('SNO')}}</th>
								<th scope="col">{{ City::attributeLabel('cit_name') }}</th>
								<th scope="col">{{ State::attributeLabel('sta_name') }}</th>
								<th scope="col">{{ District::attributeLabel('dis_name') }}</th>
								<th scope="col">{{ City::attributeLabel('cit_status') }}</th>
							</tr>
						</thead>
						<tbody>
							@php
								$districtall = $city->adminCity();
								$sno = Util::serialNo();
							@endphp
							@foreach ($districtall as $row)
								<tr>
									@php
										$badgeClass = $row->cit_status == \App\Models\Util::Enable ? 'badge-success' : 'badge-danger';
									@endphp
									<td>{{ $sno }}</td>
									<td>{{ $row->cit_name }}</td>
									<td>{{ \App\Models\State::statename($row->cit_sta_id) }}</td>
									<td>{{ \App\Models\District::DistrictName($row->cit_dis_id) }}</td>
									<td>
										<span class="badge text-md {{ $badgeClass }}">{{ \App\Models\Util::StatusData($row->cit_status) }}</span>
										<br><a href="{{ url(env('ADMIN_BASE_URL').'/edit-city/' . $row->cit_id) }}" class="btn btn-sm btn-success mt-1"><i class="fas fa-edit"></i> Edit</a>
									</td>
								</tr>
								@php $sno++ @endphp
							@endforeach
						</tbody>
					</table>

				<div class="card-footer bg-light">
					<div class="float-end">
						{{ $districtall->appends($_GET)->links('pagination::bootstrap-5') }}
					</div>
				</div>
					</div>
			</div>
		</div>
		<script>
				$(document).ready(function() {
					$('.states').select2();
					$('.states').change(function(e) {
						e.preventDefault();
						$(".district").html('<option value="">-District-</select>');
						// $(".district").val('').trigger('change');
						$('select.district').select2({
							width: '100%',
							ajax: {
								url: '/check-dis',
								dataType: 'json',
								allowClear: true,
								data: function(params) {
									return {
										q: params.term,
										states: $('.states').val(),
									};
								},
								processResults: function(data) {
									console.log(data);
									return {
										results: data
									};
								},
								cache: true
							}
						});
					});
				});
			</script>
		</div>
	</section>
@endsection
