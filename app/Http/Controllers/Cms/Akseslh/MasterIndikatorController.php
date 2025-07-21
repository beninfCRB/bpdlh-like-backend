<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\MasterIndikatorService;
use Illuminate\Http\Request;

class MasterIndikatorController extends ApiController
{
    protected $masterIndikatorService;

    public function __construct(
        MasterIndikatorService $masterIndikatorService,
        Request $request
    ) {
        $this->masterIndikatorService   =   $masterIndikatorService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.master-indikator.index");
    }

    public function create()
    {
        return view("pages.akseslh.master-indikator.create");
    }

    public function edit($id)
    {
        $data   =   $this->masterIndikatorService->getById($id);
        return view("pages.akseslh.master-indikator.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->masterIndikatorService->getById($id);
        return view("pages.akseslh.master-indikator.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'nama_indikator'     => 'required|string|max:150',
            'satuan'             => 'required|string',
            'tipe_data'          => 'required|string',
        ]);

        $result =   $this->masterIndikatorService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-indikator.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'nama_indikator'     => 'required|string|max:150',
            'satuan'             => 'required|string',
            'tipe_data'          => 'required|string',
        ]);

        $result =   $this->masterIndikatorService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-indikator.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->masterIndikatorService->delete($id);
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
        $result = $this->masterIndikatorService->restore($id);
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
