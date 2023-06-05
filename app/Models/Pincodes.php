<?php

namespace App\Models;

use \App\Models\City;
use \App\Models\State;
use \App\Models\Region;
use \App\Models\Customer;
use \App\Models\District;
use \App\Models\Cusaddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pincodes extends Model
{
	protected $table = 'ma_pincode';
	protected $primaryKey = 'pin_id';
	public $timestamps = false, $ord_report;

	use HasFactory;

	public static function attributeLabel($attr)
	{
		$ATTRALL = [
			'pin_id' => 'Primary ID',
			'pin_sta_id' => 'State',
			'pin_dis_id' => 'District',
			'pin_cit_id' => 'City',
			'officename' => 'Post Office Name',
			'pincode' => 'PinCode',
		];
		if (isset($ATTRALL[$attr])) {
			return $ATTRALL[$attr];
		}
		return $attr;
	}

	public function adminPincodes()
	{
		$request = request();
		$page_size = $request->get('n', env('PAGE_SIZE'));
		if ($page_size <= 0 || !$page_size) {
			$page_size = env('PAGE_SIZE');
		}
		$DB = DB::table($this->table);

		if ($this->pin_id) {
			$DB->where('pin_id', $this->pin_id);
		}
		if ($this->pin_sta_id != '') {
			$DB->where('pin_sta_id', $this->pin_sta_id);
		}
		if ($this->pincode) {
			$DB->where('pincode', 'LIKE', '%' . $this->pincode . '%');
		}
		if ($this->officename) {
			$DB->where('officename', 'LIKE', '%' . $this->officename . '%');
		}
		if ($this->pin_dis_id != '') {
			$DB->leftJoin('ma_district', function ($join) {
				$join->on('pin_dis_id', '=', 'dis_id');
			});
			$DB->where('dis_id', $this->pin_dis_id);
		}
		// $DB->groupBy('pincode');
		return $DB->paginate($page_size);
	}


	public function addUpdates($request) {
		if($request->post('pin_sta_id')) {
			$this->pin_sta_id = $request->post('pin_sta_id');
		}
		if($request->post('pin_dis_id')) {
			$this->pin_dis_id = $request->post('pin_dis_id');
		}
		if($this->pin_id) {
			return $this->update();
		} else {
			return $this->save();
		}
	}

	public function District()
	{
		return $this->hasOne(District::class, 'dis_id', 'pin_dis_id');
	}

	public function State()
	{
		return $this->hasOne(State::class, 'sta_id', 'pin_sta_id');
	}


}
