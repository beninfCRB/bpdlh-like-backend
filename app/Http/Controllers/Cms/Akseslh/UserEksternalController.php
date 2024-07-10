<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\UserEksternalService;
use App\Services\Akseslh\KelompokMasyarakatService;

class UserEksternalController extends ApiController
{
    protected $userEksternalService;
    protected $kelompokMasyarakatService;

    public function __construct(
        UserEksternalService $userEksternalService,
        KelompokMasyarakatService $kelompokMasyarakatService,
        Request $request
    ) {
        $this->userEksternalService   =   $userEksternalService;
        $this->kelompokMasyarakatService   =   $kelompokMasyarakatService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.pic-kelompok-masyarakat.index");
    }

    public function create()
    {
        $kelompokMasyarakat = $this->kelompokMasyarakatService->apiGetAll()->data;
        return view("pages.akseslh.pic-kelompok-masyarakat.create", compact('kelompokMasyarakat'));
    }

    public function edit($id)
    {
        $kelompokMasyarakat = $this->kelompokMasyarakatService->apiGetAll()->data;
        $data   =   $this->userEksternalService->getById($id)->data;
        return view("pages.akseslh.pic-kelompok-masyarakat.edit", compact('data', 'kelompokMasyarakat'));
    }

    public function show($id)
    {
        $data   =   $this->userEksternalService->getById($id);
        return view("pages.akseslh.pic-kelompok-masyarakat.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'kelompok_masyarakat_id'            => 'required|exists:kelompok_masyarakats,id',
            'email_user_eksternal'              => 'required|email|max:100|unique:user_eksternals,email_user_eksternal',
            'nama_user_eksternal'               => 'required|string|max:255',
            'jenis_identitas_user_eksternal'    => 'required',
            'nomor_identitas_user_eksternal'    => 'required|max:20|unique:user_eksternals',
            'nomor_hp_user_eksternal'           => 'required|max:20|unique:user_eksternals',
        ]);

        $result =   $this->userEksternalService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pic-kelompok-masyarakat.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'kelompok_masyarakat_id'            => 'required|exists:kelompok_masyarakats,id',
            'email_user_eksternal'              => 'required|email|max:100|unique:user_eksternals,email_user_eksternal,' . $id,
            'nama_user_eksternal'               => 'required|string|max:255',
            'jenis_identitas_user_eksternal'    => 'required',
            'nomor_identitas_user_eksternal'    => 'required|max:20|unique:user_eksternals,nomor_identitas_user_eksternal,' . $id,
            'nomor_hp_user_eksternal'           => 'required|max:20|unique:user_eksternals,nomor_hp_user_eksternal,' . $id,
        ]);

        $result =   $this->userEksternalService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pic-kelompok-masyarakat.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->userEksternalService->delete($id);
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
