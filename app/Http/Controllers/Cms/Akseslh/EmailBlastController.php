<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\EmailBlastService;

class EmailBlastController extends ApiController
{
    protected $emailBlastService;

    public function __construct(
        EmailBlastService $emailBlastService
    ) {
        $this->emailBlastService   =   $emailBlastService;
    }

    public function index()
    {
        return view("pages.akseslh.email-blast.index");
    }

    public function create()
    {
        $jenisKelompokMasyarakat = $this->jenisKelompokMasyarakatService->apiGetAll()->data;
        $provinsi           = $this->provinsiService->apiGetAll()->data;
        return view("pages.akseslh.kelompok-masyarakat.create", compact('jenisKelompokMasyarakat', 'provinsi'));
    }

    public function edit($id)
    {
        $data   =   $this->kelompokMasyarakatService->getById($id);
        $jenisKelompokMasyarakat = $this->jenisKelompokMasyarakatService->apiGetAll()->data;
        $provinsi           = $this->provinsiService->apiGetAll()->data;
        $kota               = $this->provinsiService->getById($data->data->provinsi_kelompok_masyarakat_id)->data ? $this->provinsiService->getById($data->data->provinsi_kelompok_masyarakat_id)->data->kota : null;
        $kecamatan          = $this->kotaService->getById($data->data->kabupaten_kelompok_masyarakat_id)->data ? $this->kotaService->getById($data->data->kabupaten_kelompok_masyarakat_id)->data->kecamatan : null;
        $kelurahan          = $this->kecamatanService->getById($data->data->kecamatan_kelompok_masyarakat_id)->data ? $this->kecamatanService->getById($data->data->kecamatan_kelompok_masyarakat_id)->data->kelurahan : null;
        return view("pages.akseslh.kelompok-masyarakat.edit", compact('data', 'jenisKelompokMasyarakat', 'provinsi', 'kota', 'kecamatan', 'kelurahan'));
    }

    public function show($id)
    {
        $data   =   $this->kelompokMasyarakatService->getById($id);
        return view("pages.akseslh.kelompok-masyarakat.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat_id'      => 'required|string',
            'kelompok_masyarakat'               => 'required|string',
            'provinsi_kelompok_masyarakat_id'   => 'required',
            'kabupaten_kelompok_masyarakat_id'  => 'required',
            'kecamatan_kelompok_masyarakat_id'  => 'required',
            'kelurahan_kelompok_masyarakat_id'  => 'required'
        ]);

        $result =   $this->kelompokMasyarakatService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('kelompok-masyarakat.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat_id'     => 'required|string',
            'kelompok_masyarakat'              => 'required|string',
            'provinsi_kelompok_masyarakat_id'   => 'required',
            'kabupaten_kelompok_masyarakat_id'  => 'required',
            'kecamatan_kelompok_masyarakat_id'  => 'required',
            'kelurahan_kelompok_masyarakat_id'  => 'required'
        ]);

        $result =   $this->kelompokMasyarakatService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('kelompok-masyarakat.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->kelompokMasyarakatService->delete($id);
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
