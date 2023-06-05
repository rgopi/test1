<?php

namespace App\Models;


use App\Models\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class State extends Model
{
	use HasFactory;
	public $timestamps = false;
	protected $table = 'ma_state';
	protected $fillable = ['sta_name', 'sta_status'];
	protected $primaryKey = 'sta_id';

	public function adminState()
	{
		$request = request();
		$page_size = $request->get('n', env('PAGE_SIZE'));
		if ($page_size <= 0 || !$page_size) {
			$page_size = env('PAGE_SIZE');
		}
		$DB = DB::table('ma_state');
		if ($this->sta_id) {
			$DB->where('sta_id', '=', $this->sta_id);
		}
		if ($this->sta_name) {
			$DB->where('sta_name', 'LIKE', '%' . $this->sta_name . '%');
		}
		if ($this->sta_status != '') {
			$DB->where('sta_status', '=', $this->sta_status);
		}
		return $DB->paginate($page_size);
	}

	public static function attributeLabel($attr)
	{
		$ATTRALL = [
			'sta_id' => 'ID',
			'sta_name' => 'State Name',
			'sta_seo_url' => 'SEO/URL',
			'sta_status' => 'Status',
			'sta_added_on' => 'Added On',
			'sta_updated_on' => 'Updated On',
		];
		if (isset($ATTRALL[$attr])) {
			return $ATTRALL[$attr];
		}
		return $attr;
	}
	public function addstate($request)
	{
		$this->sta_name = $request->sta_name;
		$this->sta_status = $request->sta_status;
		return $this->save();
	}

	public function updatestate($request)
	{
		$this->sta_name = $request->sta_name;
		$this->sta_status = $request->sta_status;
		return $this->save();
	}

    public function State()
	{
		return $this->hasOne(State::class, 'sta_id', 'pin_sta_id');
	}

	public static function statename($id)
	{
		$data = State::where('sta_id', $id)->get(['sta_name'])->first();
		return $data ? $data->sta_name : '-';
	}

	public static function ListData()
	{
		$state = State::where('sta_status', Helper::Enable)->orderBy('sta_name')->pluck('sta_name','sta_id')->toArray();
        return $state;
		// $data = [];
		// foreach ($state as $key => $st) {
		// 	$data[$st->sta_id] = $st->sta_name;
		// }
		// return $data;
	}
}
