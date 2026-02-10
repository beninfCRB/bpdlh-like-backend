<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Services\Akseslh\VideoService;
use App\Http\Controllers\ApiController;

class VideoController extends ApiController
{
    protected $videoService;

    public function __construct(
        VideoService $videoService,
        Request $request
    ) {
        $this->videoService   =   $videoService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.video.index");
    }

    public function create()
    {
        return view("pages.akseslh.video.create");
    }

    public function edit($id)
    {
        $data   =   $this->videoService->getById($id);
        return view("pages.akseslh.video.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->videoService->getById($id);
        return view("pages.akseslh.video.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'title'         => 'required|string|max:150',
            'description'   => 'required|string|max:500',
            'fileVideo'     => 'nullable',
        ]);

        if ($request->hasFile('fileVideo')) {
            $input['fileVideo'] = $request->file('fileVideo');
        }

        $result =   $this->videoService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('video.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'title'         => 'required|string|max:150',
            'description'   => 'required|string|max:500',
            'fileVideo'     => 'required|file|mimetypes:video/*',
        ]);

        $result =   $this->videoService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('video.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->videoService->delete($id);
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

    public function restore($id)
    {
        $result = $this->videoService->restore($id);
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
