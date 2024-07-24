<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\KotaService;
use Illuminate\Http\Request;

class KotaController extends ApiController
{
  protected $KotaService;

  public function __construct(
    KotaService $KotaService,
    Request $request
  ) {
    $this->KotaService    =   $KotaService;
    parent::__construct($request);
  }

  public function index(Request $request): \Illuminate\Http\JsonResponse
  {
    $result = $this->KotaService->apiGetAll();

    try {
      if ($result->success) {
        return $this->sendSuccess($result->data, $result->message, $result->code);
      }

      return $this->sendError($result->data, $result->message, $result->code);
    } catch (Exception $exception) {
      $this->sendError($exception->getMessage(), "", 500);
    }
  }

  public function show($id, Request $request)
  {
    $lang           = $request->input('lang')  ?: 'ID';

    $result = $this->KotaService->apiLang($id, $lang);

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
