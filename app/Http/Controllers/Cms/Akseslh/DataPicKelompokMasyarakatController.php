<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\ProvinsiService;
use Illuminate\Support\Facades\Validator;
use App\Imports\DataPicKelompokMasyarakatImport;
use App\Services\Akseslh\KelompokMasyarakatService;
use App\Services\Akseslh\DataPicKelompokMasyarakatService;
use App\Services\Akseslh\KecamatanService;
use App\Services\Akseslh\KelurahanService;
use App\Services\Akseslh\KotaService;

class DataPicKelompokMasyarakatController extends ApiController
{
    protected $dataPicKelompokMasyarakatService;
    protected $kelompokMasyarakatService;
    protected $provinsiService;
    protected $kotaService;
    protected $kecamatanService;
    protected $kelurahanService;

    public function __construct(
        DataPicKelompokMasyarakatService $dataPicKelompokMasyarakatService,
        KelompokMasyarakatService $kelompokMasyarakatService,
        ProvinsiService $provinsiService,
        KotaService $kotaService,
        KecamatanService $kecamatanService,
        KelurahanService $kelurahanService,
        Request $request
    ) {
        $this->dataPicKelompokMasyarakatService     =   $dataPicKelompokMasyarakatService;
        $this->kelompokMasyarakatService            =   $kelompokMasyarakatService;
        $this->provinsiService                      =   $provinsiService;
        $this->kotaService                          =   $kotaService;
        $this->kecamatanService                     =   $kecamatanService;
        $this->kelurahanService                     =   $kelurahanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.data-pic-kelompok-masyarakat.index");
    }

    public function create()
    {
        $kelompokMasyarakat = $this->kelompokMasyarakatService->apiGetAll()->data;
        $provinsi           = $this->provinsiService->apiGetAll()->data;
        $kotaKabupaten      = $this->kotaService->apiGetAll()->data;
        $kecamatan          = $this->kecamatanService->apiGetAll()->data;
        $kelurahanDesa      = $this->kelurahanService->apiGetAll()->data;
        return view("pages.akseslh.data-pic-kelompok-masyarakat.create", compact('kelompokMasyarakat', 'provinsi', 'kotaKabupaten', 'kecamatan', 'kelurahanDesa'));
    }

    public function edit($id)
    {
        $kelompokMasyarakat = $this->kelompokMasyarakatService->apiGetAll()->data;
        $data               =   $this->dataPicKelompokMasyarakatService->getById($id)->data;
        $provinsi           = $this->provinsiService->apiGetAll()->data;
        $kotaKabupaten      = $this->kotaService->apiGetAll()->data;
        $kecamatan          = $this->kecamatanService->apiGetAll()->data;
        $kelurahanDesa      = $this->kelurahanService->apiGetAll()->data;
        return view("pages.akseslh.data-pic-kelompok-masyarakat.edit", compact('data', 'kelompokMasyarakat', 'provinsi', 'kotaKabupaten', 'kecamatan', 'kelurahanDesa'));
    }

    public function show($id)
    {
        $data   =   $this->dataPicKelompokMasyarakatService->getById($id);
        return view("pages.akseslh.data-pic-kelompok-masyarakat.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'kelompok_masyarakat_id'            => 'required|exists:kelompok_masyarakats,id',
            'email_pic'                         => 'nullable|email|max:100|unique:data_pic_kelompok_masyarakats,email_pic',
            'nama_pic'                          => 'required|string|max:255',
            'jenis_identitas_pic'               => 'required',
            'nomor_identitas_pic'               => 'required|max:20|unique:data_pic_kelompok_masyarakats,nomor_identitas_pic',
            'nohp_pic'                          => 'required|max:20|unique:data_pic_kelompok_masyarakats,nohp_pic',
            'alamat_pic'                        => 'required',
            'kelurahan_pic'                     => 'required',
            'kecamatan_pic'                     => 'required',
            'kabupaten_pic'                     => 'required',
            'provinsi_pic'                      => 'required',
        ]);

        $result =   $this->dataPicKelompokMasyarakatService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pic-kelompok-masyarakat.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'kelompok_masyarakat_id'            => 'required|exists:kelompok_masyarakats,id',
            'email_pic'                         => 'nullable|email|max:100|unique:data_pic_kelompok_masyarakats,email_pic,' . $id,
            'nama_pic'                          => 'required|string|max:255',
            'jenis_identitas_pic'               => 'required',
            'nomor_identitas_pic'               => 'required|max:20|unique:data_pic_kelompok_masyarakats,nomor_identitas_pic,' . $id,
            'nohp_pic'                          => 'required|max:20|unique:data_pic_kelompok_masyarakats,nohp_pic,' . $id,
            'alamat_pic'                        => 'required',
            'kelurahan_pic'                     => 'required',
            'kecamatan_pic'                     => 'required',
            'kabupaten_pic'                     => 'required',
            'provinsi_pic'                      => 'required',
            'status_user'                       => 'required'
        ]);

        $result =   $this->dataPicKelompokMasyarakatService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pic-kelompok-masyarakat.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->dataPicKelompokMasyarakatService->delete($id);
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

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileExcel'         => 'required|file|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            # code...
            return back()->with('error', $validator->errors()->first('fileExcel'));
        }

        $input = $validator->validated();

        Excel::import(new DataPicKelompokMasyarakatImport, $input['fileExcel']);

        return back()->with('success', 'Success Import Data');
    }
}
