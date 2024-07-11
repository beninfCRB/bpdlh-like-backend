<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TematikKegiatanService;
use Illuminate\Http\Request;

class TematikKegiatanController extends ApiController
{
    protected $tematikKegiatanService;

    public function __construct(
        TematikKegiatanService $tematikKegiatanService,
        Request $request
    ) {
        $this->tematikKegiatanService   =   $tematikKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.tematik-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.tematik-kegiatan.create");
    }

    public function edit($id)
    {
        $data   =   $this->tematikKegiatanService->getById($id);
        return view("pages.akseslh.tematik-kegiatan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->tematikKegiatanService->getById($id);
        return view("pages.akseslh.tematik-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'tematik_kegiatan'    => 'required',
            'short_id'            => 'required',
            'fileImage'                => 'required',
        ]);
        $input['fileImage'] = $request->file('fileImage');

        $result =   $this->tematikKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('tematik-kegiatan.index');
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
            'jenis_kegiatan'    => 'required'
        ]);

        $result =   $this->tematikKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('tematik-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->tematikKegiatanService->delete($id);
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
