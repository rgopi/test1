<?php

namespace App\Models;

use App\Models\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $table = 'ma_district';
	protected $fillable =   ['dis_name', 'dis_status', 'dis_sta_id'];
	protected $primaryKey = 'dis_id';

	public function adminDistrict()
	{
		$request = request();
		$page_size = $request->get('n', Helper::PageSize());
		if ($page_size <= 0 || !$page_size) {
			$page_size = Helper::PageSize();
		}
		$DB = DB::table('ma_district');

		if ($this->dis_id) {
			$DB->where('dis_id', '=', $this->dis_id);
		}
		if ($this->dis_sta_id != '') {
			$DB->where('dis_sta_id', '=', $this->dis_sta_id);
		}
		if ($this->dis_name) {
			$DB->where('dis_name', 'LIKE', '%' . $this->dis_name . '%');
		}
		if ($this->dis_status != '') {
			$DB->where('dis_status', '=', $this->dis_status);
		}
		$DB->orderBy('dis_name', 'ASC');
		return $DB->paginate($page_size);
	}

	public static function attributeLabel($attr)
	{
		$ATTRALL = [
			'dis_id' => 'ID',
			'dis_name' => 'District Name',
			'sta_seo_url' => 'SEO/URL',
			'dis_sta_id' => 'State',
			'dis_status' => 'Status',
			'sta_added_on' => 'Added On',
			'sta_updated_on' => 'Updated On',
		];
		if (isset($ATTRALL[$attr])) {
			return $ATTRALL[$attr];
		}
		return $attr;
	}

	public function adddistrict($request)
	{
		$this->dis_name = $request->dis_name;
		$this->dis_sta_id = $request->dis_sta_id;
		$this->dis_status = $request->dis_status;
		return $this->save();
	}

	public function updatedistrict($request)
	{
		$this->dis_name = $request->dis_name;
		$this->dis_sta_id = $request->dis_sta_id;
		$this->dis_status = $request->dis_status;
		return $this->save();
	}

	public static function ListDataByState($sta_id, $dis_id='')
	{
		$data = [];
		if($sta_id!='') {
			if($dis_id!='') {
				$state = District::where('dis_status', Helper::Enable)->where('dis_id', $dis_id)->get();
			} else {
				$state = District::where('dis_status', Helper::Enable)->where('dis_sta_id', $sta_id)->orderBy('dis_name')->get();
			}

			$data = [];
			foreach ($state as $key => $st) {
				$data[$st->dis_id] = $st->dis_name;
			}
		}
		return $data;
	}

	public static function ListData()
	{
		$state = District::where('dis_status', Helper::Enable)->orderBy('dis_name')->get();
		$data = [];
		foreach ($state as $key => $st) {
			$data[$st->dis_id] = $st->dis_name;
		}
		return $data;
	}
	public static function DistrictName($id)
	{
		$data = District::where('dis_id', $id)->get(['dis_name'])->first();
		return $data ? $data->dis_name : '-';
	}
}
