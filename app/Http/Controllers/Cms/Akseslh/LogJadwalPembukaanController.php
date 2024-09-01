<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\LogJadwalPembukaanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LogJadwalPembukaanController extends ApiController
{
    protected $logJadwalPembukaan;

    public function __construct(
        LogJadwalPembukaanService $logJadwalPembukaan,
        Request $request
    ) {
        $this->logJadwalPembukaan   =   $logJadwalPembukaan;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.log-jadwal-pembukaan.index");
    }

    public function create()
    {
        return view("pages.akseslh.log-jadwal-pembukaan.create");
    }

    public function edit($id)
    {
        $data   =   $this->logJadwalPembukaan->getById($id);
        return view("pages.akseslh.log-jadwal-pembukaan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->logJadwalPembukaan->getById($id);
        return view("pages.akseslh.log-jadwal-pembukaan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'tanggal_awal'      => 'required',
            'jam_awal'          => 'required',
            'tanggal_akhir'     => 'required|after:tanggal_awal',
            'jam_akhir'         => 'required',
        ]);

        $result =   $this->logJadwalPembukaan->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('log-jadwal-pembukaan.index');
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

        $result =   $this->logJadwalPembukaan->update($id, $input);

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
        $result =   $this->logJadwalPembukaan->delete($id);
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
