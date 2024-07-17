<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\SatuanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SatuanController extends ApiController
{
    protected $SatuanService;

    public function __construct(
        SatuanService $SatuanService,
        Request $request
    ) {
        $this->SatuanService   =   $SatuanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.satuan.index");
    }

    public function create()
    {
        return view("pages.akseslh.satuan.create");
    }

    public function edit($id)
    {
        $data   =   $this->SatuanService->getById($id);
        return view("pages.akseslh.satuan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->SatuanService->getById($id);
        return view("pages.akseslh.satuan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'satuan'    => 'required'
        ]);
        
        $result =   $this->SatuanService->create($input);
        
        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('satuan.index');
            }
            
            dd($result->message);
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'satuan'    => 'required'
        ]);

        $result =   $this->SatuanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('satuan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->SatuanService->delete($id);
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
