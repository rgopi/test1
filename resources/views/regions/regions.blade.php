@php
	use App\Models\State;
	use App\Models\District;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
<?php
$searchFieldsCount = count(array_filter($region->getAttributes(), function($x) { return !empty($x); }));
$show =  $searchFieldsCount ? 'show' : '';
?>
	<section class="content-header">
		<a href="{{ url(env('ADMIN_BASE_URL') . '/dashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="card bg-success text-white  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header">{{ $title }}<button class="btn btn-sm btn-warning text-white float-right" type="button" id="triffer" data-toggle="collapse" data-target="#collapseExample"
                    aria-expanded="false" aria-controls="collapseExample">
                    Filter
                </button></div>
				<div class="collapse <?= $show ?>" id="collapseExample">
				<div class="card-body bg-light">
					{{ Form::open(['url' => env('ADMIN_BASE_URL') . '/regions', 'method' => 'GET', 'id' => 'search-form', 'autocomplete' => 'off']) }}
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
								{{ Form::select('reg_sta_ids', State::ListData(), $region->reg_sta_ids, ['class' => 'form-control select2', 'data-placeholder' => ' Select ' . $region::attributeLabel('reg_sta_ids'), 'multiple' => 'multiple', 'id' => 'reg_sta_ids']) }}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{{ Form::label('reg_status', $region::attributeLabel('reg_status'), ['for' => 'reg_status', 'class' => 'required']) }}
								{{ Form::select('reg_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $region->reg_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $region::attributeLabel('reg_status')]) }}
							</div>
						</div>
						<div class="col-md-12 mt-2 text-right">
							<div class="form-group">
								<?= \App\Models\Util::gridToolHtml(['new_url' => env('ADMIN_BASE_URL') . '/regionadd', 'manage_url' => env('ADMIN_BASE_URL') . '/regions']) ?>
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
									<th scope="col" style="width: 150px">{{ $region::attributeLabel('reg_name') }}</th>
									<th scope="col">{{ $region::attributeLabel('reg_sta_ids') }}</th>
									<th scope="col">{{ $region::attributeLabel('reg_status') }}</th>
								</tr>
							</thead>
							<tbody>
								@php
									$regionall = $region->adminRegion();
									$sno = \App\Models\Util::serialNo();
								@endphp
								@foreach ($regionall as $row)
									@php
										$badgeClass = $row->reg_status == \App\Models\Util::Enable ? 'badge-success' : 'badge-danger';
										$values = explode(',', $row->reg_sta_ids);
										$statesPrepare = [];
									@endphp
									<tr>
										<th>{{env('SNO')}}</th>
										<td>{{ $row->reg_name }}</td>
										{{-- @if (in_array("$row->reg_sta_ids", $values))
											{{ $row->reg_sta_ids }}
										@endif --}}
										@foreach ($values as $sta_id)
											@php
												$statesPrepare[] = \App\Models\State::statename($sta_id);
											@endphp
										@endforeach
										<td><?= implode(', ', $statesPrepare) ?></td>
										<td>
											<span class="badge text-md {{ $badgeClass }}">{{ \App\Models\Util::StatusData($row->reg_status) }}</span>
											<br>
											<a href="{{ url(env('ADMIN_BASE_URL') . '/edit-region/' . $row->reg_id) }}" class="mt-1 btn btn-sm btn-success"><i class="fas fa-edit"></i> Edit</a>
										</td>
									</tr>
									@php $sno++ @endphp
								@endforeach
							</tbody>
						</table>

						<div class="card-footer bg-light">
							<div class="float-end">
								{{ $regionall->appends($_GET)->links('pagination::bootstrap-5') }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function() {
				$('.select2').select2();
			});
		</script>
	</section>
@endsection
