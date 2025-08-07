<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\ProfilePicService;
use Illuminate\Http\Request;

class ProfilePicCOntroller extends ApiController
{
    protected $profilePicService;

    public function __construct(
        ProfilePicService $profilePicService,
        Request $request
    ) {
        $this->profilePicService   =   $profilePicService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.profile-pic.index");
    }

    public function create()
    {
        return view("pages.akseslh.profile-pic.create");
    }

    public function edit($id)
    {
        $data   =   $this->profilePicService->getById($id);
        return view("pages.akseslh.profile-pic.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->profilePicService->getById($id);
        return view("pages.akseslh.profile-pic.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->profilePicService->create($input);

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

        $result =   $this->profilePicService->update($id, $input);

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
        $result =   $this->profilePicService->delete($id);
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
        $result = $this->profilePicService->restore($id);
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
