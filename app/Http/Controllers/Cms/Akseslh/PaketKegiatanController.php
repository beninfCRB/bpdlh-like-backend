<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\PaketKegiatanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PaketKegiatanController extends ApiController
{
    protected $paketKegiatanService;

    public function __construct(
        PaketKegiatanService $paketKegiatanService,
        Request $request
    ) {
        $this->paketKegiatanService   =   $paketKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.paket-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.paket-kegiatan.create");
    }

    public function edit($id)
    {
        $data   =   $this->paketKegiatanService->getById($id);
        return view("pages.akseslh.paket-kegiatan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->paketKegiatanService->getById($id);
        return view("pages.akseslh.paket-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string',
            'short_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->paketKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('paket-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string',
            'short_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->paketKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('paket-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->paketKegiatanService->delete($id);
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
