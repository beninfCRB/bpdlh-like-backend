<?php

namespace App\Jobs;

use App\Services\AiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessOcrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $jobId;
    public string $storedPath;
    public string $originalName;

    public int $tries = 1;
    public int $timeout = 240;

    public function __construct(string $jobId, string $storedPath, string $originalName)
    {
        $this->jobId = $jobId;
        $this->storedPath = $storedPath;
        $this->originalName = $originalName;
    }

    public function handle(AiService $aiService): void
    {
        $existingState = Cache::get(self::cacheKey($this->jobId), []);

        Cache::put(
            self::cacheKey($this->jobId),
            array_merge($existingState, [
                'success' => true,
                'async' => true,
                'job_id' => $this->jobId,
                'status' => 'processing',
                'started_at' => now()->toIso8601String(),
                'updated_at' => now()->toIso8601String(),
            ]),
            now()->addMinutes(30)
        );

        $absolutePath = Storage::disk('local')->path($this->storedPath);
        $result = $aiService->ocrFromPath($absolutePath, $this->originalName);
        $isSuccess = (bool) ($result['success'] ?? false);

        Cache::put(
            self::cacheKey($this->jobId),
            array_merge($existingState, [
                'success' => $isSuccess,
                'async' => true,
                'job_id' => $this->jobId,
                'status' => $isSuccess ? 'completed' : 'failed',
                'result' => $result,
                'error' => $isSuccess ? null : ($result['error'] ?? 'OCR processing failed'),
                'updated_at' => now()->toIso8601String(),
                'finished_at' => now()->toIso8601String(),
            ]),
            now()->addMinutes(30)
        );

        Storage::disk('local')->delete($this->storedPath);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('ProcessOcrJob failed', [
            'job_id' => $this->jobId,
            'error' => $exception->getMessage(),
        ]);

        Cache::put(
            self::cacheKey($this->jobId),
            [
                'success' => false,
                'async' => true,
                'job_id' => $this->jobId,
                'status' => 'failed',
                'error' => $exception->getMessage(),
                'updated_at' => now()->toIso8601String(),
                'finished_at' => now()->toIso8601String(),
            ],
            now()->addMinutes(30)
        );

        Storage::disk('local')->delete($this->storedPath);
    }

    public static function cacheKey(string $jobId): string
    {
        return 'ai_ocr_job_' . $jobId;
    }
}
