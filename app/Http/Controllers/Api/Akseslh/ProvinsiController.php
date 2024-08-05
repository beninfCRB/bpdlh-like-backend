<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\ProvinsiService;
use Illuminate\Http\Request;

class ProvinsiController extends ApiController
{
  protected $ProvinsiService;

  public function __construct(
    ProvinsiService $ProvinsiService,
    Request $request
  ) {
    $this->ProvinsiService    =   $ProvinsiService;
    parent::__construct($request);
  }

  public function index(Request $request): \Illuminate\Http\JsonResponse
  {
    $result = $this->ProvinsiService->apiGetAll();

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
    $result = $this->ProvinsiService->getById($id);

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
