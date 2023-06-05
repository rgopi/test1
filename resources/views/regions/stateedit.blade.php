@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content">
		<section class="content-header">
			{{-- <a href="{{ url('/tnv/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a> --}}
		</section>
		<div class="container-fluid">
			<div class="card  mt-5  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header bg-success" >{{ $title }}</div>
				<div class="card-body ">
					{{ Form::open(['url' => env('ADMIN_BASE_URL').'/states', 'method' => 'GET', 'id' => 'edit-state', 'autocomplete' => 'off']) }}
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								{{ Form::label('sta_name', $state::attributeLabel('sta_name'), ['for' => 'sta_name','class' => 'required']) }}
								{{ Form::text('sta_name', $state->sta_name, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								{{ Form::label('sta_status', $state::attributeLabel('sta_status'), ['for' => 'sta_status','class' => 'required']) }}
								{{ Form::select('sta_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $state->sta_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $state::attributeLabel('sta_status')]) }}
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<a href="{{ env('ADMIN_BASE_URL').'/states' }}" class="btn btn-info">Go Back</a>
					<button type="button" id="updatestate" class="btn btn-success">Save</button>
				</div>
				{{ Form::close() }}
			</div>

			<script>
				$(document).ready(function() {
					$('#updatestate').click(function(e) {
						e.preventDefault();
						$.ajax({
							type: "POST",
							url: "<?=env('ADMIN_BASE_URL').'/edit-state/'.$state->sta_id ?>",
							data: $('#edit-state').serialize(),
							cache: false,
							success: function(response) {
								handleResponses(response, 'edit-state');
							},
							error: function(data) {
								handleResponses(data, 'edit-state');
							}
						});
					});
				});
			</script>
		</div>
	</section>
@endsection
