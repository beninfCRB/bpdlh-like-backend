<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TematikKegiatanService;
use App\Services\Akseslh\SubTematikKegiatanService;

class SubTematikKegiatanController extends ApiController
{
    protected $subTematikKegiatanService;
    protected $tematikKegiatanService;

    public function __construct(
        SubTematikKegiatanService $subTematikKegiatanService,
        TematikKegiatanService $tematikKegiatanService,
        Request $request
    ) {
        $this->subTematikKegiatanService   =   $subTematikKegiatanService;
        $this->tematikKegiatanService   =   $tematikKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.sub-tematik-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.sub-tematik-kegiatan.create");
    }

    public function edit($id)
    {
        $data   =   $this->subTematikKegiatanService->getById($id)->data;
        return view("pages.akseslh.sub-tematik-kegiatan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->subTematikKegiatanService->getById($id);
        return view("pages.akseslh.sub-tematik-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'sub_tematik_kegiatan'      => 'required',
            'short_id'                  => 'required',
            'code_id'                  => 'required',
            'deskripsi_tematik'         => 'required',
            'fileImage'                 => 'required',
        ]);
        $input['fileImage'] = $request->file('fileImage');

        $result =   $this->subTematikKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('sub-tematik-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {

        $input  =   $request->validate([
            'sub_tematik_kegiatan'      => 'required',
            'short_id'                  => 'required',
            'code_id'                   => 'required',
            'deskripsi_tematik'         => 'required',
            'fileImage'                 => 'nullable',
        ]);

        $result =   $this->subTematikKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('sub-tematik-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->subTematikKegiatanService->delete($id);
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
