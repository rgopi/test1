@php
	use App\Models\State;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL').'/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<?php
$searchFieldsCount = count(array_filter($district->getAttributes(), function($x) { return !empty($x); }));
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
					{{ Form::open(['url' => env('ADMIN_BASE_URL').'/district', 'method' => 'GET', 'id' => 'search-form', 'autocomplete' => 'off']) }}
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								{{ Form::label('dis_sta_id', $district::attributeLabel('dis_sta_id'), ['for' => 'dis_sta_id']) }}
								{{ Form::select('dis_sta_id', [null=>'--Select--'] + State::ListData(), $district->dis_sta_id, ['class' => 'form-control states']) }}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{{ Form::label('dis_name', $district::attributeLabel('dis_name'), ['for' => 'dis_name']) }}
								{{ Form::text('dis_name', $district->dis_name, ['class' => 'form-control district']) }}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{{ Form::label('dis_status', $district::attributeLabel('dis_status'), ['for' => 'dis_status']) }}
								{{ Form::select('dis_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $district->dis_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $district::attributeLabel('dis_status')]) }}
							</div>
						</div>
						<div class="col-md-12 mt-2 text-right">
							<div class="form-group">
								<?= \App\Models\Util::gridToolHtml(['new_url' => env('ADMIN_BASE_URL').'/newstate', 'manage_url' => env('ADMIN_BASE_URL').'/district', 'newmodel' => true]) ?>
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
								<th scope="col">{{$district::attributeLabel('dis_name')}}</th>
								<th scope="col">{{$district::attributeLabel('dis_sta_id')}}</th>
								<th scope="col">{{$district::attributeLabel('dis_status')}}</th>
							</tr>
						</thead>
						<tbody>
							@php
								$districtAll = $district->adminDistrict();
								$sno = \App\Models\Util::serialNo($district, 'dis_id');
							@endphp
							@foreach ($districtAll as $row)
								<tr>
									@php
										$badgeClass = $row->dis_status == \App\Models\Util::Enable ? 'badge-success' : 'badge-danger';
									@endphp
									<td>{{$sno}}</td>
									<td>{{ $row->dis_name }}</td>
									<td>{{ \App\Models\State::statename($row->dis_sta_id) }}</td>
									<td>
										<span class="badge text-md {{ $badgeClass }}">{{ \App\Models\Util::StatusData($row->dis_status) }}</span>
										<br>
										<a href="{{ url(env('ADMIN_BASE_URL').'/edit-district/' . $row->dis_id) }}" class="btn btn-sm btn-success mt-1"><i class="fas fa-edit"></i> Edit</a>
									</td>
								</tr>
								@php $sno++ @endphp
							@endforeach
						</tbody>
					</table>

				<div class="card-footer bg-light">
					<div class="float-end">
						{{ $districtAll->appends($_GET)->links('pagination::bootstrap-5') }}
					</div>
				</div>
					</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class=" modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header bg-success">
						<h5 class="modal-title" id="exampleModalLabel">Add district</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						{{ Form::open(['method' => 'post', 'id' => 'new-district-form', 'autocomplete' => 'off']) }}
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									{{ Form::label('dis_name', $district::attributeLabel('dis_name'), ['for' => 'dis_name','class' => 'required']) }}
									{{ Form::text('dis_name', $district->dis_name, ['class' => 'form-control']) }}
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									{{ Form::label('dis_sta_id', $district::attributeLabel('dis_sta_id'), ['for' => 'dis_sta_id','class' => 'required']) }}
									{{ Form::select('dis_sta_id',[null=>'--Select--'] + State::ListData(), $district->dis_sta_id, ['class' => 'form-control states']) }}
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									{{ Form::label('dis_status', $district::attributeLabel('dis_status'), ['for' => 'dis_status','class' => 'required']) }}
									{{ Form::select('dis_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $district->dis_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $district::attributeLabel('dis_status')]) }}
								</div>
							</div>
							{{ Form::close() }}
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" id="newstate" class="btn btn-success">Save</button>
					</div>
				</div>
			</div>

			<script>
				$(document).ready(function() {
					$.fn.modal.Constructor.prototype._enforceFocus = function() {};
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

					$('#newstate').click(function(e) {
						e.preventDefault();
						$.ajax({
							type: "POST",
							url: "<?= env('ADMIN_BASE_URL')?>/district",
							data: $('#new-district-form').serialize(),
							cache: false,
							success: function(response) {
								handleResponses(response, 'new-district-form');
							},
							error: function(data) {
								handleResponses(data, 'new-district-form');
							}
						});
					});
				});

			</script>
		</div>
	</section>
@endsection
