<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailBlastJob;
use App\Models\PivotEmailBlast;
use Illuminate\Console\Command;

class EmailBlastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:blast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email blast 50 email per menit pada 28 April';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // if (now()->format('Y-m-d') !== '2025-04-28') return 0;

        $toSend = PivotEmailBlast::with('pengajuan')
            ->whereNull('sent_at')
            ->take(50)
            ->get();

        foreach ($toSend as $log) {
            SendEmailBlastJob::dispatch($log);
        }
    }
}
