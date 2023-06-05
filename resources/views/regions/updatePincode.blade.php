@php
	use App\Models\State;
	use App\Models\District;
@endphp
@extends('layouts.adminlte')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL').'/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header">{{ $title }}</div>
				<div class="card-body bg-light">
					{{ Form::open(['method'=>'post', 'id'=>'pincode-form', 'autocomplete'=>'off', 'class'=>'default-action-from', 'url' => Route('updatePincode', ['id' => $Pincodes->pin_id])]) }}
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('pincode', $Pincodes::attributeLabel('pincode'), ['for' => 'pincode','class' => '']) }}
								<div class="form-control bg-gray">{{$Pincodes->pincode}}</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('pin_sta_id', $Pincodes::attributeLabel('pin_sta_id'), ['for' => 'pin_sta_id','class' => 'required']) }}
								{{ Form::select('pin_sta_id', [null => '--Select--'] + State::ListData(), $Pincodes->pin_sta_id, ['class' => 'form-control states']) }}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('pin_dis_id', $Pincodes::attributeLabel('pin_dis_id'), ['for' => 'pin_dis_id','class' => 'required']) }}
								{{ Form::select('pin_dis_id', [null => '--Select--'] + District::ListData(), $Pincodes->pin_dis_id, ['class' => 'district form-control']) }}
							</div>
						</div>
						<div class="col-md-12">
							<a href="{{Route('pincode')}}" class="btn btn-info">Back</a>
							<button type="submit" class="btn btn-success">Save</button>
						</div>
						{{ Form::close() }}
					</div>
				</div>

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
					});
				</script>
			</div>
		</div>
	</section>
@endsection
