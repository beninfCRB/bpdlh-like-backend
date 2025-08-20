<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\ProfileService;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\ProfilePicService;

class ProfilePicController extends ApiController
{
    protected $profilePicService;
    protected $profileService;

    public function __construct(
        ProfilePicService $profilePicService,
        ProfileService $profileService,
        Request $request
    ) {
        parent::__construct($request);
        $this->profilePicService   =   $profilePicService;
        $this->profileService   =   $profileService;
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

    public function tolak_pengajuan_perubahan_profil($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_field' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        return $this->sendSuccess($request->all(), 'Berhasil', 200);
    }

    public function tolak_profil($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pengajuan_kegiatan_id' => 'required',
            'catatan_log'         => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->user()->email_pic . ' ' . json_encode($validator->errors()->all()), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $input['user'] = $request->user();

        $result =   $this->profileService->delete_profile($id, $input);

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

    public function destroy($id, Request $request): \Illuminate\Http\JsonResponse
    {

        $result =   $this->profileService->delete_profile($id, $input);

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
