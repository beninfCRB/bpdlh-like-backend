<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TematikKegiatanService;
use App\Services\Akseslh\SubTematikKegiatanService;
use App\Services\Akseslh\MasterSubTematikKegiatanService;

class MasterSubTematikKegiatanController extends ApiController
{
    protected $masterSubTematikKegiatanService;
    protected $subTematikKegiatanService;
    protected $tematikKegiatanService;

    public function __construct(
        MasterSubTematikKegiatanService $masterSubTematikKegiatanService,
        SubTematikKegiatanService $subTematikKegiatanService,
        TematikKegiatanService $tematikKegiatanService,
        Request $request
    ) {
        $this->masterSubTematikKegiatanService      =   $masterSubTematikKegiatanService;
        $this->subTematikKegiatanService            =   $subTematikKegiatanService;
        $this->tematikKegiatanService               =   $tematikKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.master-sub-tematik-kegiatan.index");
    }

    public function create()
    {
        $tematikKegiatan    = $this->tematikKegiatanService->getAllAttr()->data;
        $subTematikKegiatan = $this->subTematikKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.master-sub-tematik-kegiatan.create", compact('tematikKegiatan', 'subTematikKegiatan'));
    }

    public function edit($id)
    {
        $data               =   $this->masterSubTematikKegiatanService->getById($id)->data;
        $tematikKegiatan    = $this->tematikKegiatanService->getAllAttr()->data;
        $subTematikKegiatan = $this->subTematikKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.master-sub-tematik-kegiatan.edit", compact('data', 'tematikKegiatan', 'subTematikKegiatan'));
    }

    public function show($id)
    {
        $data   =   $this->masterSubTematikKegiatanService->getById($id);
        return view("pages.akseslh.master-sub-tematik-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $checkData = $request->all();
        $input  =   $request->validate([
            'tematik_kegiatan_id'       => ['required', 'exists:tematik_kegiatans,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_sub_tematik_kegiatans')
                    ->where('tematik_kegiatan_id', $value)
                    ->where('sub_tematik_kegiatan_id', $checkData['sub_tematik_kegiatan_id'])
                    ->exists()
                ) {
                    $fail('The combination of tematik and sub tematik already exists.');
                }
            }],
            'sub_tematik_kegiatan_id'   => ['required', 'exists:sub_tematik_kegiatans,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_sub_tematik_kegiatans')
                    ->where('tematik_kegiatan_id', $checkData['tematik_kegiatan_id'])
                    ->where('username', $value)
                    ->exists()
                ) {
                    $fail('The combination of tematii and sub tematik already exists.');
                }
            }],
            'short_id'                  => 'required|numeric|min:1',
            'deskripsi_tematik'         => 'required|max:255',
        ]);

        $result =   $this->masterSubTematikKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-sub-tematik-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $checkData = $request->all();
        $checkData['id'] = $id;
        $input  =   $request->validate([
            'tematik_kegiatan_id'       => ['required', 'exists:tematik_kegiatans,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_sub_tematik_kegiatans')
                    ->where('tematik_kegiatan_id', $value)
                    ->where('sub_tematik_kegiatan_id', $checkData['sub_tematik_kegiatan_id'])
                    ->where('id', '<>', $checkData['id'])
                    ->exists()
                ) {
                    $fail('The combination of tematik and sub tematik already exists.');
                }
            }],
            'sub_tematik_kegiatan_id'   => ['required', 'exists:sub_tematik_kegiatans,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_sub_tematik_kegiatans')
                    ->where('tematik_kegiatan_id', $checkData['tematik_kegiatan_id'])
                    ->where('username', $value)
                    ->where('id', '<>', $checkData['id'])
                    ->exists()
                ) {
                    $fail('The combination of tematii and sub tematik already exists.');
                }
            }],
            'short_id'                  => 'required|numeric|min:1',
            'deskripsi_tematik'         => 'required|max:255',
        ]);

        $result =   $this->masterSubTematikKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-sub-tematik-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->masterSubTematikKegiatanService->delete($id);
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
