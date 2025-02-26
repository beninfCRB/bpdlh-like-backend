<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Notifications\VerifikasiValidasiNotification;
use App\Services\Akseslh\VerifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifikasiController extends ApiController
{
	protected $VerifikasiService;

	public function __construct(
		VerifikasiService $VerifikasiService,
		Request $request
	) {
		$this->VerifikasiService    =   $VerifikasiService;
		parent::__construct($request);
	}

	public function index(Request $request): \Illuminate\Http\JsonResponse
	{
		$result = $this->VerifikasiService->getAllAttr();

		try {
			if ($result->success) {
				return $this->sendSuccess($result->data, $result->message, $result->code);
			}

			return $this->sendError($result->data, $result->message, $result->code);
		} catch (Exception $exception) {
			$this->sendError($exception->getMessage(), "", 500);
		}
	}

	public function show($id)
	{
		$result = $this->VerifikasiService->apiLang($id);

		try {
			if ($result->success) {
				return $this->sendSuccess($result->data, $result->message, $result->code);
			}

			return $this->sendError($result->data, $result->message, $result->code);
		} catch (Exception $exception) {
			$this->sendError($exception->getMessage(), "", 500);
		}
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse
	{
		$validator = Validator::make($request->all(), [
			'paket_kegiatan_id'         => 'required|exists:paket_kegiatans,id',
			'judul_pengajuan_kegiatan'  => 'required|string|max:500',
			'provinsi_kegiatan'         => 'required',
			'catatan_log'        => 'required',
		]);

		if ($validator->fails()) {
			# code...
			return $this->sendError(null, $validator->getMessageBag(), 422);
		}

		$input          = $validator->validated();

		$tanggalArray   = explode(" - ", $input["tanggal_kegiatan"]);
		$waktuArray     = explode(" - ", $input["waktu_kegiatan"]);

		//add new key for required field in table
		$input["user_akseslh_id"] = $request->user()->id;
		$input["tanggal_mulai_kegiatan"]    = $tanggalArray[0];
		$input["tanggal_akhir_kegiatan"]    = $tanggalArray[1];
		$input["time_mulai_kegiatan"]      = $waktuArray[0];
		$input["time_akhir_kegiatan"]      = $waktuArray[1];

		//eliminate unnecessary key 
		unset($input["tanggal_kegiatan"]);
		unset($input["waktu_kegiatan"]);

		$result = $this->VerifikasiService->create($input);

		try {
			if ($result->success) {
				return $this->sendSuccess($result->data, $result->message, $result->code);
			}

			return $this->sendError($result->data, $result->message, $result->code);
		} catch (Exception $exception) {
			$this->sendError($exception->getMessage(), "", 500);
		}
	}

	public function update($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'catatan_log'               => 'required',
			'status'                    => 'required'
		]);

		if ($validator->fails()) {
			# code...
			\Sentry\captureMessage('Validate Message: ' . $request->user()->email_pic . ' ' . json_encode($validator->errors()->all()), \Sentry\Severity::warning());
			return $this->sendError(null, $validator->getMessageBag(), 422);
		}

		$input  = $validator->validated();

		$input['user_akseslh_id']  	= $request->user()->id;
		$input['user_akseslh']		= $request->user();

		$result = $this->VerifikasiService->updateTemp($id, $input);

		try {
			if ($result->success) {

				return $this->sendSuccess($result->data, $result->message, $result->code);
			}

			return $this->sendError($result->data, $result->message, $result->code);
		} catch (Exception $exception) {
			$this->sendError($exception->getMessage(), "", 500);
		}
	}
}
