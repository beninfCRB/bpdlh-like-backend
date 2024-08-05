<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\KelurahanService;
use Illuminate\Http\Request;

class KelurahanController extends ApiController
{
  protected $KelurahanService;

  public function __construct(
    KelurahanService $KelurahanService,
    Request $request
  ) {
    $this->KelurahanService    =   $KelurahanService;
    parent::__construct($request);
  }

  public function index(Request $request): \Illuminate\Http\JsonResponse
  {
    $result = $this->KelurahanService->apiGetAll();

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
    $result = $this->KelurahanService->getById($id);

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
