<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\StatusPernikahanService;
use Illuminate\Http\Request;

class StatusPernikahanController extends ApiController
{
    protected $statusPernikahanService;

    public function __construct(
        StatusPernikahanService $statusPernikahanService,
        Request $request
    ) {
        $this->statusPernikahanService   =   $statusPernikahanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.status-pernikahan.index");
    }

    public function create()
    {
        return view("pages.akseslh.status-pernikahan.create");
    }

    public function edit($id)
    {
        $data   =   $this->statusPernikahanService->getById($id);
        return view("pages.akseslh.status-pernikahan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->statusPernikahanService->getById($id);
        return view("pages.akseslh.status-pernikahan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'status_pernikahan'     => 'required|string|max:150',
        ]);

        $result =   $this->statusPernikahanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('status-pernikahan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'status_pernikahan'     => 'required|string|max:150',
        ]);

        $result =   $this->statusPernikahanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('status-pernikahan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->statusPernikahanService->delete($id);
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
