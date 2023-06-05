<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\State;
use App\Models\District;
use App\Models\Pincodes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{

	public function dashboard(Request $request)
	{
		$title = 'Admin Dashboard';
		return view('dashboard', ['title' => $title]);
	}

    public function stateindex(Request $request)
	{
		$title = 'Manage State';
		$state = new State;
		if ($request->post()) {
			$this->validate(
				$request,
				[
					'sta_name' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.]+$/|unique:ma_state,sta_name',
					'sta_status' => 'required',
				],
				[
					// required
					'sta_name.unique' => State::attributeLabel('sta_name') . ' is already Existing. ',
					'sta_name.required' => State::attributeLabel('sta_name') . ' is required. ',
					'sta_name.max' => State::attributeLabel('sta_name') . ' must not be greater than 60 characters. ',
					'sta_name.regex' => State::attributeLabel('sta_name') . '  format is invalid. ',
					// max
					'sta_status.required' => State::attributeLabel('sta_status') . ' is required. ',
					'sta_status.max' => State::attributeLabel('sta_status') . ' must not be greater than 60 characters. ',
					'sta_status.regex' => State::attributeLabel('sta_status') . '  format is invalid. ',
				]
			);
			$validator = Validator::make($request->all(), [
				'q' => 'nullable|max:60|regex:/^[a-zA-Z\ \.]+$/',
			]);
			if ($validator->fails()) {
				return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
			} else {
				if ($state->addstate($request)) {
					$otherErro['status'] = 200;
					$otherErro['message'] = 'The state saved successfully.';
					Session::flash('success', $otherErro['message']);
				} else {
					$otherErro['staus'] = 103;
					$otherErro['message'] = 'Something Went Wrong';
				}
				return response()->json($otherErro, Response::HTTP_BAD_REQUEST);
			}
		}
		$state->sta_id = $request->get('sta_id');
		$state->sta_name = $request->get('sta_name');
		$state->sta_status = $request->get('sta_status');
		$state->sta_updated_on = $request->get('sta_updated_on');
		return view('regions.states', compact(['title', 'state']));
	}
	public function stateedit(Request $request, $id)
	{
		$title = 'Edit State';
		$state = State::where('sta_id', $id)->first();
		if (!$state) {
			abort(404, "#$id record was not found");
		} else {
			if ($request->post()) {
				$this->validate(
					$request,
					[
						'sta_name' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.]+$/|unique:ma_state,sta_name,' . $id . ',sta_id',
						'sta_status' => 'required|max:60|min:2|regex:/^[a-zA-Z0-9\ \.]+$/',
					],
					[
						// required
						'sta_name.unique' => State::attributeLabel('sta_name') . ' is already Existing. ',
						'sta_name.required' => State::attributeLabel('sta_name') . ' is required. ',
						'sta_name.max' => State::attributeLabel('sta_name') . ' must not be greater than 60 characters. ',
						'sta_name.regex' => State::attributeLabel('sta_name') . '  format is invalid. ',
						// max
						'sta_status.required' => State::attributeLabel('sta_status') . ' is required. ',
						'sta_status.max' => State::attributeLabel('sta_status') . ' must not be greater than 60 characters. ',
						'sta_status.regex' => State::attributeLabel('sta_status') . '  format is invalid. ',
					]
				);
				$validator = Validator::make($request->all(), [
					'q' => 'nullable|max:60|regex:/^[a-zA-Z\ \.]+$/',
				]);
				if ($validator->fails()) {
					return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
				} else {
					if ($state->updatestate($request)) {
						$otherErro['status'] = 200;
						$otherErro['message'] = 'The state updated successfully.';
						$otherErro['redirect'] = url(env('ADMIN_BASE_URL') . "/states");
						Session::flash('success', $otherErro['message']);
					} else {
						$otherErro['staus'] = 103;
						$otherErro['message'] = 'Something Went Wrong';
					}
					return response()->json($otherErro, Response::HTTP_BAD_REQUEST);
				}
			}
		}
		return view('regions.stateedit', compact(['title', 'state']));
	}

	public function districtindex(Request $request)
	{
		$title = 'Manage District';
		$district = new District;
		if ($request->post()) {
			$this->validate(
				$request,
				[
					'dis_name' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.]+$/',
					'dis_sta_id' => 'required:integer',
					'dis_status' => 'required:integer',
				],
				[
					// required
					'dis_name.required' => District::attributeLabel('dis_name') . ' is required. ',
					'dis_name.max' => District::attributeLabel('dis_name') . ' must not be greater than 60 characters. ',
					'dis_name.regex' => District::attributeLabel('dis_name') . '  format is invalid. ',
					// max
					'dis_status.required' => District::attributeLabel('dis_status') . ' is required. ',
					// format
					'dis_sta_id.required' => District::attributeLabel('dis_sta_id') . ' is required. ',
					'dis_sta_id.integer' => District::attributeLabel('dis_sta_id') . '  format is invalid. ',
				]
			);
			$validator = Validator::make($request->all(), [
				'q' => 'nullable|max:60|regex:/^[a-zA-Z\ \.]+$/',
			]);
			if ($validator->fails()) {
				return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
			} else {
				if ($district->adddistrict($request)) {
					$otherErro['status'] = 200;
					$otherErro['message'] = 'The District saved successfully.';
					Session::flash('success', $otherErro['message']);
				} else {
					$otherErro['staus'] = 103;
					$otherErro['message'] = 'Something Went Wrong';
				}
				return response()->json($otherErro, Response::HTTP_BAD_REQUEST);
			}
		}
		$district->dis_id = $request->get('dis_id');
		$district->dis_name = $request->get('dis_name');
		$district->dis_sta_id = $request->get('dis_sta_id');
		$district->dis_status = $request->get('dis_status');
		return view('regions.districts', compact(['title', 'district']));
	}

	public function pincode(Request $request)
	{
		$title = 'Manage Pincode';
		$Pincodes = new Pincodes;

		$Pincodes->pin_id = $request->get('pin_id');
		$Pincodes->pincode = $request->get('pincode');
		$Pincodes->officename = $request->get('officename');
		$Pincodes->pin_sta_id = $request->get('pin_sta_id');
		$Pincodes->pin_dis_id = $request->get('pin_dis_id');

		return view('regions.pincode', compact(['title', 'Pincodes']));
	}

	public function updatePincode(Request $request, $id='') {
		$title = 'Update Pincode';
		$Pincodes = new Pincodes;
		if($id!='') {
			$Pincodes = Pincodes::find($id);
			if(!$Pincodes) {
				abort(404, "#$id record was not found");
			} else {
				if ($request->post()) {
					$otherErro = [];
					$this->validate(
						$request,
						[
							'pin_sta_id' => 'required',
							'pin_dis_id' => 'required',
						],
						[
							'pin_sta_id.required' => Pincodes::attributeLabel('pin_sta_id') . ' is required. ',
							'pin_dis_id.required' => Pincodes::attributeLabel('pin_dis_id') . ' is required. ',
						]
					);
					$validator = Validator::make($request->all(), [
						'q' => 'nullable|max:60|regex:/^[a-zA-Z\ \.]+$/',
					]);
					if ($validator->fails()) {
						return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
					} else {
						if ($Pincodes->addUpdates($request)) {
							$otherErro['status'] = 200;
							$otherErro['message'] = 'The Pincodes saved successfully.';
							$otherErro['redirect'] = Route('pincode') . '?pin_sta_id='.$Pincodes->pin_sta_id;
							Session::flash('success', $otherErro['message']);
						} else {
							$otherErro['staus'] = 103;
							$otherErro['message'] = 'Something Went Wrong';
						}
					}
					return response()->json($otherErro, Response::HTTP_BAD_REQUEST);
				}
			}
		}
		return view('regions.updatePincode', compact(['title', 'Pincodes']));
	}

	public function districtedit(Request $request, $id)
	{
		$title = 'Edit District';
		$district = District::where('dis_id', $id)->first();
		if (!$district) {
			abort(404, "#$id record was not found");
		} else {
			if ($request->post()) {
				$this->validate(
					$request,
					[
						'dis_name' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.]+$/|unique:ma_district,dis_name,' . $id . ',dis_id',
						'dis_sta_id' => 'required|max:60|min:2|regex:/^[a-zA-Z0-9\ \.]+$/',
						'dis_status' => 'required',
					],
					[
						// required
						'dis_name.unique' => District::attributeLabel('dis_name') . ' is already Existing. ',
						'dis_name.required' => District::attributeLabel('dis_name') . ' is required. ',
						'dis_name.max' => District::attributeLabel('dis_name') . ' must not be greater than 60 characters. ',
						'dis_name.regex' => District::attributeLabel('dis_name') . '  format is invalid. ',
						// max
						'dis_status.required' => District::attributeLabel('dis_status') . ' is required. ',
						'dis_status.max' => District::attributeLabel('dis_status') . ' must not be greater than 60 characters. ',
						'dis_status.regex' => District::attributeLabel('dis_status') . '  format is invalid. ',
						// format
						'dis_sta_id.required' => District::attributeLabel('dis_sta_id') . ' is required. ',
						'dis_sta_id.max' => District::attributeLabel('dis_sta_id') . ' must not be greater than 60 characters. ',
						'dis_sta_id.regex' => District::attributeLabel('dis_sta_id') . '  format is invalid. ',
					]
				);
				$validator = Validator::make($request->all(), [
					'q' => 'nullable|max:60|regex:/^[a-zA-Z\ \.]+$/',
				]);
				if ($validator->fails()) {
					return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
				} else {
					if ($district->updatedistrict($request)) {
						$otherErro['status'] = 200;
						$otherErro['message'] = 'The district updated successfully.';
						$otherErro['redirect'] = url(env('ADMIN_BASE_URL') . "/district");
						Session::flash('success', $otherErro['message']);
					} else {
						$otherErro['staus'] = 103;
						$otherErro['message'] = 'Something Went Wrong';
					}
					return response()->json($otherErro, Response::HTTP_BAD_REQUEST);
				}
			}
		}
		return view('regions.districtedit', compact(['title', 'district']));
	}

    public function checkdis(Request $request)
	{
		$states = $request->get('states', '');
		$district = $request->get('q');
		$json = [];
		$status = 422;
		if ($states && $district) {
			$sta = District::where('dis_sta_id', $states)->where('dis_status', Helper::Enable)->where('dis_name', 'LIKE', $district . '%')->orderBy('dis_id')->get();
			if ($district && $sta) {
				foreach ($sta as $key => $row) {
					$json[] = ['id' => $row->dis_id, 'text' => $row->dis_name];
				}
			}
			if(count($json)==0) {
				$row = District::where('dis_status', Helper::Enable)->where('dis_name', 'OTHER')->first();
				if($row) {
					$json[] = ['id' => $row->dis_id, 'text' => $row->dis_name];
				}
			}
		}
		return response()->json($json);
	}

} // end class RegionController
