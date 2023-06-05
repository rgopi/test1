<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Helper extends Model
{
	const Enable = 1, Disable = 0;
	const Male = 1, Female = 0;
	const Active = 1, Block = 0;
	const CREDIT = 1, WITHDRAW = 0;
	const Transfered = 1, Processing = 0;
	const SAVINGS = 0, CURRENT = 1;
	const PAGE_SUMMARY='Current Page Total';
	const ADDRESS_REGEX='/^[a-zA-Z0-9\ \.\&\/\\\\(\)\,\-\#\@]+$/';
	const ADDRESS1_MAX=45;
	const ADDRESS2_MAX=60;
	const PageSize = 10;
	const MAX_ADDRESS = 3;

	use HasFactory;

	public static function ruleCode($type)
	{
		$data = [
			'reqstate' => 'required|max:60',

			'req_reg_name' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.]+$/',

			'req_name' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.]+$/',
			'req_app_number' => 'required|max:60|regex:/^[a-zA-Z0-9\ \.\&\/\\\]+$/',
			'req_number' => 'required|max:20|regex:/^[0-9\.]+$/',
			'req_mobile' => 'required|max:20|regex:/^[0-9\+]{10,20}$/',
			'req_landline' => 'required|max:20|regex:/^[0-9\+]{7,20}$/',
			'req_email' => 'required|email|max:300',
			'req_two_state' => ('required|integer|digits_between:' . self::Disable . ',' . self::Enable),

			'null_name' => 'nullable|max:60|regex:/^[a-zA-Z0-9\ \.]+$/',
			'null_number' => 'nullable|integer|min:1',
			'null_mobile' => 'nullable|max:20|regex:/^[0-9\+]{10,20}$/',
			'null_email' => 'nullable|email|max:300',
			'null_two_state' => ('nullable|integer|digits_between:' . self::Disable . ',' . self::Enable),
		];
		return isset($data[$type]) ? $data[$type] : '';
	}

	public static function StatusData($key = "")
	{
		$data = [
			self::Enable => 'Enable',
			self::Disable => 'Disable',
		];
		return isset($data[$key]) ? $data[$key] : $data;
	}

	public static function GenderData($key = "")
	{
		$data = [
			self::Male => 'Male',
			self::Female => 'Female',
		];
		return isset($data[$key]) ? $data[$key] : $data;
	}


	public static function Statususer($key = "")
	{
		$data = [
			self::Active => 'Active',
			self::Block => 'Block',
		];
		return isset($data[$key]) ? $data[$key] : $data;
	}

	public static function paymentlist($key = "")
	{
		$data = [
			self::Transfered => 'Transfered',
			self::Processing => 'Processing',
		];
		return isset($data[$key]) ? $data[$key] : $data;
	}
	public static function bankaccountlist($key = "")
	{
		$data = [
			self::SAVINGS => 'SAVINGS',
			self::CURRENT => 'CURRENT',
		];
		return isset($data[$key]) ? $data[$key] : $data;
	}

	public static function jsLog($params = [])
	{
		$scriptTag = true;
		$code = '';
		$log = '';
		$ready = false;
		extract($params, EXTR_IF_EXISTS);
		$jsLog = '';

		$jsLog .= $scriptTag ? '<script>' : '';
		$jsLog .= $ready ? '$(document).ready(function () {' : '';
		$jsLog .= $code ? $code . ';' : '';
		$jsLog .= $log ? 'console.log("' . addslashes($log) . '");' : '';
		$jsLog .= $ready ? '});' : '';
		$jsLog .= $scriptTag ? '</script>' : '';
		return $jsLog;

	}

	public static function ajaxLink($params = [])
	{
		$inside = isset($params['inside']) ? ($params['inside']) : 'Submit';
		$CLASS = isset($params['class']) ? (' class="doajax ' . $params['class'] . '" ') : ' class="doajax" ';
		if (isset($params['override'])) {
			$CLASS = ' class="' . $params['override'] . '" ';
		}
		$ID = isset($params['id']) ? (' id="' . $params['id'] . '" ') : '';
		// $result, $data, $aurl, $partial, $before, $after, $replace, $append, $prepend, $once, $method, $reqtype;
		$dataAttr = '';
		foreach ($params as $attrKey => $attrVal) {
			if ($attrKey == 'class') {
				continue;
			}
			if ($attrKey == 'edata') {
				$attrVal = Helper::encrypt($attrVal);
			}
			$dataAttr .= 'data-' . $attrKey . '="' . $attrVal . '" ';
		}
		return ('<a href="javascript:;" ' . $CLASS . $ID . ' ' . $dataAttr . '>' . $inside . '</a>');
	}

	public static function ajaxButton($params = [])
	{
		$inside = isset($params['inside']) ? ($params['inside']) : 'Submit';
		$TYPE = isset($params['type']) ? ($params['type']) : 'submit';
		$CLASS = isset($params['class']) ? (' class="doajax ' . $params['class'] . '" ') : ' class="doajax" ';
		if (isset($params['override'])) {
			$CLASS = ' class="' . $params['override'] . '" ';
		}
		$ID = isset($params['id']) ? (' id="' . $params['id'] . '" ') : '';
		// $result, $data, $aurl, $partial, $before, $after, $replace, $append, $prepend, $once, $method, $reqtype;
		$dataAttr = '';
		foreach ($params as $attrKey => $attrVal) {
			if ($attrKey == 'class') {
				continue;
			}
			if ($attrKey == 'edata') {
				$attrVal = Helper::encrypt($attrVal);
			}
			$dataAttr .= 'data-' . $attrKey . '="' . $attrVal . '" ';
		}
		return ('<button '.$TYPE.' ' . $CLASS . $ID . ' ' . $dataAttr . '>' . $inside . '</button>');
	}
	/*
	- encrypting a string is to be encrypt by AES algorithm with using
	? .env -> APP_KEY
	@ plaintext as string
	< return string
	*/
	public static function encrypt($plaintext)
	{
		if (is_array($plaintext)) {
			$plaintext = json_encode($plaintext);
		}
		$dirty = array("+", "/", "=");
		$clean = array("-p", "-s", "-e");
		$key = env('APP_KEY');
		$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
		$iv = openssl_random_pseudo_bytes($ivlen);
		$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
		$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
		$ciphertext = str_replace($dirty, $clean, trim($ciphertext));
		return $ciphertext;
	}

	/*
	- decrypting a string by AES algorithm with using
	? .env -> APP_KEY
	@ plaintext as string, [json_decode] as boolen
	< return string | object
	*/
	public static function decrypt($ciphertext, $json_decode = false)
	{
		try {
			$dirty = array("+", "/", "=");
			$clean = array("-p", "-s", "-e");
			$key = env('APP_KEY');
			$ciphertext = str_replace($clean, $dirty, $ciphertext);
			$c = base64_decode($ciphertext);
			$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
			$iv = substr($c, 0, $ivlen);
			$hmac = substr($c, $ivlen, $sha2len = 32);
			$ciphertext_raw = substr($c, $ivlen + $sha2len);
			$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
			$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
			if (hash_equals($hmac, $calcmac)) { // timing attack safe comparison
				return $json_decode ? json_decode($original_plaintext) : $original_plaintext;
			}
		} catch (\Throwable $th) {
			Log::info('decrypt error = ' . print_r($th, 1));
			return false;
		}
	}

	public static function staticEncrypt($simple_string)
	{
		// Storingthe cipher method
		$ciphering = "AES-128-CTR";
		// Using OpenSSl Encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		// Non-NULL Initialization Vector for encryption
		$encryption_iv = '1234567891011121';
		// Storing the encryption key
		$encryption_key = env('APP_KEY');
		// Using openssl_encrypt() function to encrypt the data
		$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
		$dirty = array("+", "/", "=");
		$clean = array("_p", "_s", "_e");
		$encryption = str_replace($dirty, $clean, $encryption);
		return $encryption;
	}

	public static function staticDecrypt($encryption)
	{
		try {
			$dirty = array("+", "/", "=");
			$clean = array("_p", "_s", "_e");
			$encryption = str_replace($clean, $dirty, $encryption);
			// Storingthe cipher method
			$ciphering = "AES-128-CTR";
			// Using OpenSSl Encryption method
			$iv_length = openssl_cipher_iv_length($ciphering);
			$options = 0;
			// Non-NULL Initialization Vector for encryption
			$encryption_iv = '1234567891011121';
			// Storing the encryption key
			$decryption_key = env('APP_KEY');
			// Using openssl_encrypt() function to encrypt the data
			$decryption = openssl_decrypt($encryption, $ciphering, $decryption_key, $options, $encryption_iv);
			return $decryption;
		} catch (\Throwable $th) {
			Log::info('staticDecrypt error = ' . print_r($th, 1));
			return false;
		}
	}

	public static function randomString($length, $inc = 'all')
	{
		if (!is_numeric($length) || $length < 1) {
			return '';
		}

		if ($inc == 'number') {
			$characters = '0123456789';
		} else if ($inc == 'smallnum') {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		} else if ($inc == 'bignum') {
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		} else if ($inc == 'small') {
			$characters = 'abcdefghijklmnopqrstuvwxyz';
		} else if ($inc == 'big') {
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		} else if ($inc == 'both') {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		} else {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}

		$randstring = '';
		for ($i = 1; $i <= $length; $i++) {
			$offset = rand(1, strlen($characters));
			if (!isset($characters[$offset])) {
				$i--;
				continue;
			} else {
				$randstring .= $characters[$offset];
			}
		}
		return $randstring;
	}

	public static function dateFormatSave($dateString = '')
	{
		if ($dateString) {
			$dateFormatSave = new \DateTime($dateString);
		} else {
			$dateFormatSave = new \DateTime('now', new \DateTimeZone('+05:30'));
		}
		return $dateFormatSave->format(env('DATE_FORMAT'));
	}

	public static function datetimeFormatSave($dateString = '')
	{
		if ($dateString) {
			$datetimeFormatSave = new \DateTime($dateString);
		} else {
			$datetimeFormatSave = new \DateTime('now', new \DateTimeZone('+05:30'));
		}
		return $datetimeFormatSave->format(env('NOW_FORMAT'));
	}

	public static function timeFormatSave($dateString = '')
	{
		if ($dateString) {
			$timeFormatSave = new \DateTime($dateString);
		} else {
			$timeFormatSave = new \DateTime('now', new \DateTimeZone('+05:30'));
		}
		return $timeFormatSave->format(env('TIME_FORMAT'));
	}

	public static function dateFormat($dateString, $format = 'd/m/Y')
	{
		$date = strtotime($dateString);
		return date($format, $date);
	}

	public static function datetimeFormat($dateString, $format = 'd/m/Y H:i:s')
	{
		$date = strtotime($dateString);
		return date($format, $date);
	}

	public static function timeFormat($dateString, $format = 'H:i:s')
	{
		$date = strtotime($dateString);
		return date($format, $date);
	}

	public static function stateDistrictAajx()
	{
		$jsCode = "
<script>
	$(document).ready(function() {
		$('.states').select2();
		$('.states').change(function(e) {
			e.preventDefault();
			$('.district').val('').trigger('change');
		});
		$('select.district').select2({
			width: '100%',
			ajax: {
				url: '" . url('/check-dis') . "',
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
</script>";
		return $jsCode;
	}




	public static function scecureString($CONTENT)
	{
		$cleanData = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $CONTENT);
		$cleanData = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $CONTENT);
		$cleanData = str_replace('<?php', '', $cleanData);
		$cleanData = str_replace(' ?>', '', $cleanData);
		$cleanData = str_replace(';?>', '', $cleanData);
		$cleanData = str_replace('?>', '', $cleanData);
		$cleanData = str_replace('<?=', '', $cleanData);
		$cleanData = str_replace('<%=', '', $cleanData);
		$cleanData = str_replace('<%', '', $cleanData);
		$cleanData = str_replace('<script', '', $cleanData);
		$cleanData = str_replace('<iframe', '', $cleanData);
		$cleanData = str_replace('</iframe', '', $cleanData);
		$cleanData = trim(strip_tags($cleanData));
		return $cleanData;
	}

	public static function dataTableJsCode($params=[]) {

		// $('#myTable').on( 'draw.dt', function () {
		//     alert( 'Table redrawn' );
		// } );
		$jsCode = "";
		$orientation = 'landscape'; // landscape
		$pageSize = 'A4'; // LEGAL
		$selector = '.table-responsive .table'; // #id or .class-name
		extract($params, EXTR_IF_EXISTS);
		$jsCode =
		"$(document).ready(function () {
			$.fn.dataTable.ext.errMode = 'none';
			dtable = $('$selector').on('error.dt', function (e, settings, techNote, message ) {
				console.log( 'An error has been reported by DataTables: ', message );
			}).DataTable({
				searching: false,
				paging: false,
				info: false,
				ordering: false,
				dom: 'Bfrtip',
				rowGroup: {
					dataSrc: 'group'
				},
				buttons: [
					$.extend(true, {}, buttonCommon, {
						extend: 'copy',
						text: 'Copy',
						className: 'btn btn-info', orientation: '$orientation', pageSize: '$pageSize' //, columnDefs: [{ targets: -1 }]
					}),
					$.extend(true, {}, buttonCommon, {
						extend: 'excel',
						text: 'Excel',
						className: 'btn btn-success', orientation: '$orientation', pageSize: '$pageSize' //, columnDefs: [{ targets: -1 }]
					}),
					$.extend(true, {}, buttonCommon, {
						extend: 'pdfHtml5',
						text: 'Pdf',
						className: 'btn btn-danger', orientation: '$orientation', pageSize: '$pageSize' //, columnDefs: [{ targets: -1 }]
					}),
					// {text: 'Print', extend: 'print', autoPrint: false,}
				],
				customize : function(doc) {doc.pageMargins = ['0.2', '0.2', '0.2', '0.2']; },
			});

			$('$selector').on('draw', function () {
				console.log( 'Table redrawn' );
			});
		});
		";
		// { extend: 'copy', className: 'btn btn-info' },
		// { extend: 'excel', className: 'btn btn-success' },
		// hold { extend: 'pdf', className: 'btn btn-danger' },
		// { extend: 'print', exportOptions: { columns: ':visible' },}

		return $jsCode;
	}

	public static function number($number) {
		return number_format((float) $number, 2, '.', '');
	}

	public static function nextSetData($params=[]) {
		// $weekFromDay = date('Y-m-d', strtotime('next Wednesday'));
		// $date = new \DateTime($weekFromDay);
		// $date->add(new \DateInterval('P6D')); // P1D means a period of 1 day
		// $weekToDay = $date->format('Y-m-d');
		$currentDate   = time();
		$nextWednesday = strtotime('next wednesday', $currentDate);
		$weekToDay = date('Y-m-d', $nextWednesday);
		return $weekToDay;
	}

	public static function addDays($params=[]) {
		$dateString = date('Y-m-d');
		$days = 7;
		extract($params, EXTR_IF_EXISTS);
		$date = new \DateTime($dateString);
		$date->add(new \DateInterval('P'.$days.'D')); // P1D means a period of 1 day
		$addDaysDate = $date->format('Y-m-d');
		return $addDaysDate;
	}

	public static function pinState($params=[]) {
		$pinId = 'add_pincode';
		$formId = 'formId';
		$route = Route('loadRegions');
		extract($params, EXTR_IF_EXISTS);
		$jsCode = '$("#'.$pinId.'").change(function (e) {
			e.preventDefault();
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "'.$route.'",
				data: {"_token": $(\'meta[name="_token"]\').attr("content"), "pincode": $(this).val()},
				success: function(data) {
					loadRegion(data, "#'.$formId.'");
				},
				error: function(data) {
					loadRegion(data, "#'.$formId.'");
				}
			});
		});
		';
		return $jsCode;
	}

    public static function serialNo()
	{
		$page = request()->get('page', 0);
		$SIZE = request()->get('n', Helper::PageSize());
		if ($page == 0) {
			$sno = 1;
		} else {
			$sno = (($page - 1) * $SIZE) + 1;
		}
		return $sno;
	}

    public static function gridToolHtml($params = [])
	{
		$n = request()->get('n', 10);
		$new_url = isset($params['new_url']) ? $params['new_url'] : '';
		$new_btn = $new_url ? ('<a href="' . url($new_url) . '" class="btn-sm btn btn-secondary"><i class="fa far fa-newspaper"></i> Add New</a>') : '';
		$newmodel = '<button type="button" class="btn-sm btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add New</button>';
		if (isset($params['newmodel'])) {
			$new_btn = $newmodel;
		}
		$manage_url = isset($params['manage_url']) ? $params['manage_url'] : '';
		$manage_btn = $manage_url ? '  <a href="' . url($manage_url) . '" class="btn-sm btn btn-warning">Clear Filter</a>' : '';
		return
		'<div class="row"><div class="col-md-10 text-left mt-1">' .
			$new_btn . $manage_btn . '
		<button type="submit" class="btn-sm btn btn-md btn-success">Go</button>
		<input type="hidden" name="page" value="' . request()->get('page', 1) . '">
		</div>
		<div class="col-md-2 text-right mt-1">
		<select name="n" id="number" class="form-control n-select">
			<option value="">- Page Size -</option>
			<option ' . ($n == 10 ? 'selected' : '') . ' value="10">10</option>
			<option ' . ($n == 25 ? 'selected' : '') . ' value="25">25</option>
			<option ' . ($n == 50 ? 'selected' : '') . ' value="50">50</option>
			<option ' . ($n == 100 ? 'selected' : '') . ' value="100">100</option>
			<option ' . ($n == 150 ? 'selected' : '') . ' value="150">150</option>
			<option ' . ($n == 200 ? 'selected' : '') . ' value="200">200</option>
		</select>
        </div>
        </div>
		';
	}

	public static function PageSize()
	{
		return self::PageSize;
	}


} // end class Helper
