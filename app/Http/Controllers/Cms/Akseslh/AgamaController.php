<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\AgamaService;
use Illuminate\Http\Request;

class AgamaController extends ApiController
{
    protected $agamaService;

    public function __construct(
        AgamaService $agamaService,
        Request $request
    ) {
        $this->agamaService   =   $agamaService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.agama.index");
    }

    public function create()
    {
        return view("pages.akseslh.agama.create");
    }

    public function edit($id)
    {
        $data   =   $this->agamaService->getById($id)->data;
        return view("pages.akseslh.agama.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->agamaService->getById($id);
        return view("pages.akseslh.agama.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'agama'     => 'required|string|max:150',
        ]);

        $result =   $this->agamaService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('agama.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'agama'     => 'required|string|max:150',
        ]);

        $result =   $this->agamaService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('agama.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->agamaService->delete($id);
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
