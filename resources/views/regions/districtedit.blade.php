@php
	use App\Models\State;
@endphp
@extends('layouts.admin.adminPanel')
@section('content')
	<section class="content">
		<section class="content-header">
			<a href="{{ Route('adminDashboard') }}" class="btn btn-warning"><i class="fas fa-home"></i></a>
		</section>
		<div class="container-fluid">
			<div class="card  text-dark mt-5  "id="neu-1" style=" border-radius: 10px;">
				<div class="card-header bg-success" >{{ $title }}</div>
				<div class="card-body ">
					{{ Form::open([ 'id' => 'edit-district', 'url' => (env('ADMIN_BASE_URL') . '/edit-district/' .$district->dis_id), 'autocomplete' => 'off', 'class'=>'default-action-from']) }}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('dis_name', $district::attributeLabel('dis_name'), ['for' => 'dis_name','class' => 'required']) }}
								{{ Form::text('dis_name', $district->dis_name, ['class' => 'form-control upper-text']) }}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('dis_sta_id', $district::attributeLabel('dis_sta_id'), ['for' => 'dis_sta_id','class' => 'required']) }}
								{{ Form::select('dis_sta_id',[null=>'--Select--'] + State::ListData(), $district->dis_sta_id, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('dis_status', $district::attributeLabel('dis_status'), ['for' =>'dis_status','class' => 'required']) }}
								{{ Form::select('dis_status', [null => '-- Select --'] + \App\Models\Util::StatusData(), $district->dis_status, ['class' => 'form-control', 'data-placeholder' => ' Select ' . $district::attributeLabel('dis_status')]) }}
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
					<button type="button" id="updatedistrict" class="btn btn-success">Save</button>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</section>
@endsection
