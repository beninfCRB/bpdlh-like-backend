<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\EmailPhpService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TransaksiPenyaluranEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to, $subject, $data, $altBody = '', $view;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $data, $altBody = '', $view)
    {
        //
        $this->to = $to;
        $this->subject = $subject;
        $this->data = $data;
        $this->altBody = $altBody;
        $this->view = $view;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailPhpService $emailPhpService)
    {
        //
        $emailPhpService->transaksiPenyaluran($this->to, $this->subject, $this->data, $this->altBody, $this->view);
    }
}
