<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Exports\TolakPengajuanDanProfilTemplate;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TolakPengajuanDanProfilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TolakPengajuanDanProfilController extends ApiController
{
    protected $tolakPengajuanDanProfilService;

    public function __construct(
        TolakPengajuanDanProfilService $tolakPengajuanDanProfilService,
        Request $request
    ) {
        $this->tolakPengajuanDanProfilService   =   $tolakPengajuanDanProfilService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.tolak-pengajuan-dan-profil.index");
    }

    public function create()
    {
        return view("pages.akseslh.tolak-pengajuan-dan-profil.create");
    }

    public function edit($id)
    {
        $data   =   $this->tolakPengajuanDanProfilService->getById($id);
        return view("pages.akseslh.tolak-pengajuan-dan-profil.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->tolakPengajuanDanProfilService->getById($id);
        return view("pages.akseslh.tolak-pengajuan-dan-profil.show", compact('data'));
    }

    public function proses()
    {
        $result = $this->tolakPengajuanDanProfilService->proses();

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors()->all(), 422);
        }

        $input = $validator->validated();

        $input['username'] = $request->user()->id;

        $result =   $this->tolakPengajuanDanProfilService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->tolakPengajuanDanProfilService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('tolak-pengajuan-dan-profil.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->tolakPengajuanDanProfilService->delete($id);
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

    public function restore($id)
    {
        $result = $this->tolakPengajuanDanProfilService->restore($id);
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

    public function template()
    {
        return Excel::download(new TolakPengajuanDanProfilTemplate, 'tolak_pengajuan_dan_profil_template.xlsx');
    }
}
