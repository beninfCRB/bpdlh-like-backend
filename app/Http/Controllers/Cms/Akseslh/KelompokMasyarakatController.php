<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\KelompokMasyarakatService;
use App\Services\Akseslh\JenisKelompokMasyarakatService;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;

class KelompokMasyarakatController extends ApiController
{
    protected $kelompokMasyarakatService;
    protected $jenisKelompokMasyarakatService;

    public function __construct(
        KelompokMasyarakatService $kelompokMasyarakatService,
        JenisKelompokMasyarakatService $jenisKelompokMasyarakatService,
        Request $request
    ) {
        $this->kelompokMasyarakatService   =   $kelompokMasyarakatService;
        $this->jenisKelompokMasyarakatService   =   $jenisKelompokMasyarakatService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.kelompok-masyarakat.index");
    }

    public function create()
    {
        $jenisKelompokMasyarakat = $this->jenisKelompokMasyarakatService->apiGetAll()->data;
        // dd($jenisKelompokMasyarakat);
        return view("pages.akseslh.kelompok-masyarakat.create", compact('jenisKelompokMasyarakat'));
    }

    public function edit($id)
    {
        $data   =   $this->kelompokMasyarakatService->getById($id);
        return view("pages.akseslh.kelompok-masyarakat.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->kelompokMasyarakatService->getById($id);
        return view("pages.akseslh.kelompok-masyarakat.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'akseslh_jenis_kelompok_masyarakat_id'     => 'required|string',
            'kelompok_masyarakat'                      => 'required|string',
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
            'jenis_kelompok_masyarakat'     => 'required|string',
            'short_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->kelompokMasyarakatService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-kelompok-masyarakat.index');
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
