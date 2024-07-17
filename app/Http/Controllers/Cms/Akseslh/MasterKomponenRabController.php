<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\MasterKomponenRabService;
use App\Services\Akseslh\JenisKomponenRabService;
use App\Services\Akseslh\SatuanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class MasterKomponenRabController extends ApiController
{
    protected $MasterKomponenRabService;
    protected $JenisKomponenRabService;
    protected $SatuanService;

    public function __construct(
        MasterKomponenRabService $MasterKomponenRabService,
        JenisKomponenRabService $JenisKomponenRabService,
        SatuanService $SatuanService,
        Request $request
    ) {
        $this->MasterKomponenRabService   =   $MasterKomponenRabService;
        $this->JenisKomponenRabService   =   $JenisKomponenRabService;
        $this->SatuanService   =   $SatuanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.master-komponen-rab.index");
    }

    public function create()
    {
        $JenisKomponenRab = $this->JenisKomponenRabService->getAllAttr()->data;
        $Satuan = $this->SatuanService->getAllAttr()->data;
        return view("pages.akseslh.master-komponen-rab.create", compact('JenisKomponenRab', 'Satuan'));
    }

    public function edit($id)
    {
        $data   =   $this->MasterKomponenRabService->getById($id);
        $JenisKomponenRab = $this->JenisKomponenRabService->getAllAttr()->data;
        $Satuan = $this->SatuanService->getAllAttr()->data;
        return view("pages.akseslh.master-komponen-rab.edit", compact('data', 'JenisKomponenRab', 'Satuan'));
    }

    public function show($id)
    {
        $data   =   $this->MasterKomponenRabService->getById($id);
        return view("pages.akseslh.master-komponen-rab.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_komponen_rab'    => 'required',
            'satuan'                => 'required',
            'master_komponen_rab'   => 'required',
            'standar_harga_unit'    => 'required',
        ]);

        $result =   $this->MasterKomponenRabService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-komponen-rab.index');
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
            'jenis_komponen_rab'    => 'required',
            'satuan'                => 'required',
            'master_komponen_rab'   => 'required',
            'standar_harga_unit'    => 'required',
        ]);
        $result =   $this->MasterKomponenRabService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-komponen-rab.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->MasterKomponenRabService->delete($id);
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
