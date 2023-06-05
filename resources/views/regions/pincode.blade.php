@php
	use App\Models\State;
	use App\Models\District;
@endphp
@extends('layouts.adminlte')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL') . '/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<?php
	$searchFieldsCount = count(
		array_filter($Pincodes->getAttributes(), function ($x) {
			return $x!='';
		}),
	);
	$show = $searchFieldsCount ? 'show' : '';
	?>
	<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header">{{ $title }}<button class="btn btn-sm btn-warning text-white float-right" type="button" id="triffer" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Filter</button></div>
				<div class="collapse <?= $show ?>" id="collapseExample">
					<div class="card-body bg-light">
						{{ Form::open(['url' => env('ADMIN_BASE_URL') . '/pincode', 'method' => 'GET', 'id' => 'search-form', 'autocomplete' => 'off']) }}
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									{{ Form::label('pincode', $Pincodes::attributeLabel('pincode'), ['for' => 'pincode']) }}
									{{ Form::text('pincode', $Pincodes->pincode, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{{ Form::label('pin_sta_id', $Pincodes::attributeLabel('pin_sta_id'), ['for' => 'pin_sta_id']) }}
									{{ Form::select('pin_sta_id', [null => '--Select--'] + State::ListData(), $Pincodes->pin_sta_id, ['class' => 'form-control states']) }}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{{ Form::label('pin_dis_id', $Pincodes::attributeLabel('pin_dis_id'), ['for' => 'pin_dis_id', 'class' => '']) }}
									{{ Form::select('pin_dis_id', [null => '--Select--'] + District::ListData(), $Pincodes->pin_dis_id, ['class' => 'district form-control']) }}
								</div>
							</div>
							<div class="col-md-12 mt-2 text-right">
								<div class="form-group">
									<?= \App\Models\Helper::gridToolHtml(['manage_url' => env('ADMIN_BASE_URL') . '/pincode']) ?>
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
									<th>{{ env('SNO') }}</th>
									<th>{{ $Pincodes::attributeLabel('pincode') }}</th>
									<th>{{ $Pincodes::attributeLabel('pin_dis_id') }}</th>
									<th>{{ $Pincodes::attributeLabel('pin_sta_id') }}</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@php
									$PincodesAll = $Pincodes->adminPincodes();
									$sno = \App\Models\Helper::serialNo();
								@endphp
								@foreach ($PincodesAll as $rowObj)
									<tr>
										@php
											$row = $Pincodes->find($rowObj->pin_id)->load('District');
										@endphp
										<td>{{ $sno }}</td>
										<td>{{ $row->pincode }}</td>
										<td>{{ $row->District->dis_name ?? '-' }}</td>
										<td>{{ $row->State->sta_name ?? '-' }}</td>
										<td>
											<a href="{{ Route('updatePincode', ['id' => $row->pin_id]) }}" class="btn btn-sm btn-success mt-1"><i class="fas fa-edit"></i> Edit</a>
										</td>
									</tr>
									@php $sno++ @endphp
								@endforeach
							</tbody>
						</table>
						<div class="card-footer bg-light">
							<div class="float-end">
								{{ $PincodesAll->appends($_GET)->links('pagination::bootstrap-5') }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		$(document).ready(function() {
			$('.states').select2();

			$('.states').change(function(e) {
				e.preventDefault();
				$(".district").html('<option value="">District</option>');
				$(".district").val('').trigger('change');
			});

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
			$('#newstate').click(function(e) {
				e.preventDefault();
				$.ajax({
					type: "POST",
					url: "<?= env('ADMIN_BASE_URL') ?>/district",
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
@endsection
