<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\KecamatanService;
use Illuminate\Http\Request;

class KecamatanController extends ApiController
{
  protected $KecamatanService;

  public function __construct(
    KecamatanService $KecamatanService,
    Request $request
  ) {
    $this->KecamatanService    =   $KecamatanService;
    parent::__construct($request);
  }

  public function index(Request $request): \Illuminate\Http\JsonResponse
  {
    $result = $this->KecamatanService->apiGetAll();

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
    $result = $this->KecamatanService->getById($id);

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
