<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\LogMasaSanggahService;
use Illuminate\Http\Request;

class LogMasaSanggahController extends ApiController
{
    protected $logMasaSanggahService;

    public function __construct(
        LogMasaSanggahService $logMasaSanggahService,
        Request $request
    ) {
        $this->logMasaSanggahService   =   $logMasaSanggahService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.log-masa-sanggah.index");
    }

    public function create()
    {
        return view("pages.akseslh.log-masa-sanggah.create");
    }

    public function edit($id)
    {
        $data   =   $this->logMasaSanggahService->getById($id);
        return view("pages.akseslh.log-masa-sanggah.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->logMasaSanggahService->getById($id);
        return view("pages.akseslh.log-masa-sanggah.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'tanggal_awal'      => 'required',
            'tanggal_akhir'     => 'required|after_or_equal:tanggal_awal',
            'jam_awal'          => 'required',
            'jam_akhir'         => 'required',
            'batas_pengajuan'   => 'required',
        ]);

        $result =   $this->logMasaSanggahService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('log-masa-sanggah.index');
            }
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan'    => 'required',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->logMasaSanggahService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->logMasaSanggahService->delete($id);
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
