<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Cms\Akseslh\JenisPekerjaan\CreateJenisPekerjaanRequest;
use App\Http\Requests\Cms\Akseslh\JenisPekerjaan\UpdateJenisPekerjaanRequest;
use App\Services\Akseslh\JenisPekerjaanService;
use Illuminate\Http\Request;

class JenisPekerjaanController extends ApiController
{
    protected $jenisPekerjaan;

    public function __construct(
        JenisPekerjaanService $jenisPekerjaan,
        Request $request
    ) {
        $this->jenisPekerjaan   =   $jenisPekerjaan;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.jenis-pekerjaan.index");
    }

    public function create()
    {
        return view("pages.akseslh.jenis-pekerjaan.create");
    }

    public function edit($id)
    {
        $data   =   $this->jenisPekerjaan->getById($id);
        return view("pages.akseslh.jenis-pekerjaan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->jenisPekerjaan->getById($id);
        return view("pages.akseslh.jenis-pekerjaan.show", compact('data'));
    }

    public function store(CreateJenisPekerjaanRequest $request)
    {
        $input  =   $request->all();

        $result =   $this->jenisPekerjaan->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-pekerjaan.index');
            }
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, UpdateJenisPekerjaanRequest $request)
    {
        $input      =   $request->all();

        $result     =   $this->jenisPekerjaan->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-pekerjaan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->jenisPekerjaan->delete($id);
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
