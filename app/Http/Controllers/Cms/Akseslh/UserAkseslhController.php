<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\UserAkseslhService;
use App\Services\Akseslh\KelompokMasyarakatService;

class UserAkseslhController extends ApiController
{
    protected $userAkseslhService;

    public function __construct(
        UserAkseslhService $userAkseslhService,
        Request $request
    ) {
        $this->userAkseslhService   =   $userAkseslhService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.user-akseslh.index");
    }

    public function create()
    {
        return view("pages.akseslh.user-akseslh.create");
    }

    public function edit($id)
    {
        $data   =   $this->userAkseslhService->getById($id)->data;
        return view("pages.akseslh.user-akseslh.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->userAkseslhService->getById($id);
        return view("pages.akseslh.user-akseslh.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'email'                             => 'required|email|max:100|unique:user_akseslhs,email',
            'nama_pic'                          => 'required|string|max:255',
            'role_user'                         => 'required',
        ]);

        $result =   $this->userAkseslhService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('user-akseslh.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'email'                             => 'required|email|max:100|unique:user_akseslhs,email,' . $id,
            'nama_pic'                          => 'required|string|max:255',
            'role_user'                         => 'required',
            'status_user'                       => 'required'
        ]);

        $result =   $this->userAkseslhService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('user-akseslh.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->userAkseslhService->delete($id);
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
