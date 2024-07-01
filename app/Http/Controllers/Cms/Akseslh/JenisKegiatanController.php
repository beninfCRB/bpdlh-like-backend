<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\CreateCareerRequest;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Service\Announcement\Career\CareerService;
use Illuminate\Http\Request;

class JenisKegiatanController extends ApiController
{
    protected $careerService;

    public function __construct(
        CareerService $careerService,
        Request $request
    ) {
        $this->careerService   =   $careerService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("announcement.career.index");
    }

    public function create()
    {
        return view("announcement.career.create");
    }

    public function edit($id)
    {
        $data   =   $this->careerService->getById($id);
        return view("announcement.career.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->careerService->getById($id);
        return view("announcement.career.show", compact('data'));
    }

    public function store(CreateCareerRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->careerService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update(UpdateCareerRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->careerService->update($input['id'], $input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->careerService->delete($id);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function updatePublish(Request $request)
    {
        $input  =   $request->all();

        $result =   $this->careerService->updatePublish($input['id'], $input['isPublish']);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
