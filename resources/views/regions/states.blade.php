@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL').'/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<?php
$searchFieldsCount = count(array_filter($state->getAttributes(), function($x) { return !empty($x); }));
$show =  $searchFieldsCount ? 'show' : '';
?>
		<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header" >{{ $title }}<button class="btn btn-sm btn-warning text-white float-right" type="button" id="triffer" data-toggle="collapse" data-target="#collapseExample"
                    aria-expanded="false" aria-controls="collapseExample">
                    Filter
                </button></div>
				<div class="collapse <?= $show ?>" id="collapseExample">
				<div class="card-body bg-light">
					{{ Form::open(['url' => env('ADMIN_BASE_URL').'/states', 'method' => 'GET', 'id' => 'search-form', 'autocomplete' => 'off']) }}
					<div class="row">
						<div class="col-md-1">
							<div class="form-group">
								{{ Form::label('sta_id', 'ID', ['for' => 'sta_id']) }}
								{{ Form::text('sta_id', $state->sta_id, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{{ Form::label('sta_name', $state::attributeLabel('sta_name'), ['for' => 'sta_name']) }}
								{{ Form::text('sta_name', $state->sta_name, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{{ Form::label('sta_status', $state::attributeLabel('sta_status'), ['for' => 'sta_status']) }}
								{{ Form::select('sta_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $state->sta_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $state::attributeLabel('sta_status')]) }}
							</div>
						</div>
						<div class="col-md-12 mt-2 text-right">
							<div class="form-group">
								<?= \App\Models\Util::gridToolHtml(['new_url' => env('ADMIN_BASE_URL').'/newstate', 'manage_url' => env('ADMIN_BASE_URL').'/states', 'newmodel' => true]) ?>
							</div>
						</div>
					</div>
					{{ Form::close() }}
				</div>
			</div>
				<div class="card-body bg-light">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>{{env('SNO')}}</th>
								<th scope="col">{{$state::attributeLabel('sta_name')}}</th>
								<th scope="col">{{$state::attributeLabel('sta_status')}}</th>
							</tr>
						</thead>
						<tbody>
							@php
								$stateAll = $state->adminState();
								$sno = \App\Models\Util::serialNo();
							@endphp
							@foreach ($stateAll as $row)
								<tr>
									@php
										$badgeClass = $row->sta_status == \App\Models\Util::Enable ? 'badge-success' : 'badge-danger';
									@endphp
									<td>{{ $sno }}</td>
									<td>{{ $row->sta_name }}</td>
									<td>
										<span class="badge text-md {{ $badgeClass }}">{{ \App\Models\Util::StatusData($row->sta_status) }}</span>
										<br>
										<a href="{{ url(env('ADMIN_BASE_URL').'/edit-state/' . $row->sta_id) }}" class="btn btn-sm btn-success mt-1"><i class="fas fa-edit"></i> Edit</a>
									</td>
								</tr>
								@php $sno++ @endphp
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="card-footer bg-light">
					<div class="float-end">
						{{ $stateAll->appends($_GET)->links('pagination::bootstrap-5') }}
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class=" modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header bg-success">
						<h5 class="modal-title" id="exampleModalLabel">Add State</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						{{ Form::open(['method' => 'post', 'id' => 'new-state-form', 'autocomplete' => 'off']) }}
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{{ Form::label('sta_name', $state::attributeLabel('sta_name'), ['for' => 'sta_name','class' => 'required']) }}
									{{ Form::text('sta_name', $state->sta_name, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{{ Form::label('sta_status', $state::attributeLabel('sta_status'), ['for' => 'sta_status','class' => 'required']) }}
									{{ Form::select('sta_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $state->sta_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $state::attributeLabel('sta_status')]) }}
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
					$('#newstate').click(function(e) {
						e.preventDefault();
						$.ajax({
							type: "POST",
							url: "<?= env('ADMIN_BASE_URL')?>/states",
							data: $('#new-state-form').serialize(),
							cache: false,
							success: function(response) {
								handleResponses(response, 'new-state-form');
							},
							error: function(data) {
								handleResponses(data, 'new-state-form');
							}
						});
					});
				});
			</script>
		</div>
	</section>
@endsection
