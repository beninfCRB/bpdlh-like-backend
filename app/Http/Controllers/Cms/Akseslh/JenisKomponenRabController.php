<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\JenisKomponenRabService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class JenisKomponenRabController extends ApiController
{
    protected $JenisKomponenRabService;

    public function __construct(
        JenisKomponenRabService $JenisKomponenRabService,
        Request $request
    ) {
        $this->JenisKomponenRabService   =   $JenisKomponenRabService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.jenis-komponen-rab.index");
    }

    public function create()
    {
        return view("pages.akseslh.jenis-komponen-rab.create");
    }

    public function edit($id)
    {
        $data   =   $this->JenisKomponenRabService->getById($id);
        return view("pages.akseslh.jenis-komponen-rab.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->JenisKomponenRabService->getById($id);
        return view("pages.akseslh.jenis-komponen-rab.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_komponen_rab'    => 'required'
        ]);
        
        $result =   $this->JenisKomponenRabService->create($input);
        
        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-komponen-rab.index');
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
            'jenis_komponen_rab'    => 'required'
        ]);

        $result =   $this->JenisKomponenRabService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-komponen-rab.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->JenisKomponenRabService->delete($id);
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
