<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\MasterDataBankService;

class MasterDataBankController extends ApiController
{
    protected $MasterDataBankService;

    public function __construct(
        MasterDataBankService $MasterDataBankService,
        Request $request
    ) {
        $this->MasterDataBankService      =   $MasterDataBankService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.master-data-bank.index");
    }

    public function create()
    {
        return view("pages.akseslh.master-data-bank.create");
    }

    public function edit($id)
    {
        $data   =   $this->MasterDataBankService->getById($id)->data;
        return view("pages.akseslh.master-data-bank.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->MasterDataBankService->getById($id);
        return view("pages.akseslh.master-data-bank.show", compact('data'));
    }

    public function store(Request $request)
    {
        $checkData = $request->all();

        $input  =   $request->validate([
            'nama_bank' => 'required|max:255',
            'kode_bank' => 'required|max:10',
        ]);
        $result =   $this->MasterDataBankService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-data-bank.index');
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
            'nama_bank' => 'required|max:255',
            'kode_bank' => 'required|max:10',
        ]);
        $result =   $this->MasterDataBankService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-data-bank.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->MasterDataBankService->delete($id);
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
