<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\JenisKelompokMasyarakatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class JenisKelompokMasyarakat extends ApiController
{
    protected $jenisKelompokMasyarakatService;

    public function __construct(
        JenisKelompokMasyarakatService $jenisKelompokMasyarakatService,
        Request $request
    ) {
        $this->jenisKelompokMasyarakatService   =   $jenisKelompokMasyarakatService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.jenis-kelompok-masyarakat.index");
    }

    public function create()
    {
        return view("pages.akseslh.jenis-kelompok-masyarakat.create");
    }

    public function edit($id)
    {
        $data   =   $this->jenisKelompokMasyarakatService->getById($id);
        return view("pages.akseslh.jenis-kelompok-masyarakat.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->jenisKelompokMasyarakatService->getById($id);
        return view("pages.akseslh.jenis-kelompok-masyarakat.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->jenisKelompokMasyarakatService->create($input);

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

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->jenisKelompokMasyarakatService->update($id, $input);

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
        $result =   $this->jenisKelompokMasyarakatService->delete($id);
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
