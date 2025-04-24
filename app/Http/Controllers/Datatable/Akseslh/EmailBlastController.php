<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\EmailBlastService;

class EmailBlastController extends Controller
{
    protected $emailBlastService;

    /**
     * @param EmailBlastService $EmailBlastService
     */
    public function __construct(EmailBlastService $emailBlastService)
    {
        $this->emailBlastService = $emailBlastService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->emailBlastService->getAll();
    }
}
