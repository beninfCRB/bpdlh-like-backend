<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Cms\Akseslh\Pendidikan\CreatePendidikanRequest;
use App\Http\Requests\Cms\Akseslh\Pendidikan\UpdatePendidikanRequest;
use App\Services\Akseslh\PendidikanService;
use Illuminate\Http\Request;

class PendidikanController extends ApiController
{
    protected $pendidikan;

    public function __construct(
        PendidikanService $pendidikan,
        Request $request
    ) {
        $this->pendidikan   =   $pendidikan;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.pendidikan.index");
    }

    public function create()
    {
        return view("pages.akseslh.pendidikan.create");
    }

    public function edit($id)
    {
        $data   =   $this->pendidikan->getById($id);
        return view("pages.akseslh.pendidikan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->pendidikan->getById($id);
        return view("pages.akseslh.pendidikan.show", compact('data'));
    }

    public function store(CreatePendidikanRequest $request)
    {
        $input  =   $request->all();

        $result =   $this->pendidikan->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pendidikan.index');
            }
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, UpdatePendidikanRequest $request)
    {
        $input      =   $request->all();

        $result     =   $this->pendidikan->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pendidikan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->pendidikan->delete($id);
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
