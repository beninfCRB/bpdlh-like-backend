<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Exports\TestimonialExport;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TestimonialService;
use Maatwebsite\Excel\Facades\Excel;

class TestimonialController extends ApiController
{
    protected $testimonialService;

    public function __construct(
        TestimonialService $testimonialService,
        Request $request
    ) {
        $this->testimonialService   =   $testimonialService;
        parent::__construct($request);
    }

    public function index()
    {
        $testimonials = Testimonial::query()->with([
            'data_pic_kelompok_masyarakat' => function ($query) {
                $query->withTrashed();
            },
            'data_pic_kelompok_masyarakat.kelompok_masyarakat' => function ($query) {
                $query->withTrashed();
            },
            'data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis' => function ($query) {
                $query->withTrashed();
            },
        ])->orderBy('created_at', 'DESC')->paginate(10);

        return view("pages.akseslh.testimonial.index", compact('testimonials'));
    }

    public function create()
    {
        return view("pages.akseslh.testimonial.create");
    }

    public function edit($id)
    {
        $data   =   $this->testimonialService->getById($id);
        return view("pages.akseslh.testimonial.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->testimonialService->getById($id);
        return view("pages.akseslh.testimonial.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->testimonialService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('testimonial.index');
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

        $result =   $this->testimonialService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('testimonial.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->testimonialService->delete($id);
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
        $result = $this->testimonialService->restore($id);
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

    public function export()
    {
        return Excel::download(new TestimonialExport(), 'testimonial.xlsx');
    }

    public function import(Request $request)
    {

        $request->validate([
            'fileImport' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('fileImport');

        $result = $this->testimonialService->import($file);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('testimonial.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
