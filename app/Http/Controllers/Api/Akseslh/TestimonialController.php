<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TestimonialService;
use Illuminate\Http\Request;

class TestimonialController extends ApiController
{
    protected $testimonialService;

    public function __construct(
        TestimonialService $testimonialService,
        Request $request
    ) {
        $this->testimonialService    =   $testimonialService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->testimonialService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
