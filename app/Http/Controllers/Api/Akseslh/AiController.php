<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessOcrJob;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AiController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'healthy' => $this->aiService->healthCheck(),
        ]);
    }

    public function ocr(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:10240', // 10MB
        ]);

        try {
            $result = $this->aiService->ocr($request->file('file'));
            $status = ($result['success'] ?? false) ? 200 : 502;

            return response()->json($result, $status);
        } catch (Throwable $e) {
            Log::error('OCR API error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'OCR processing failed',
            ], 500);
        }
    }

    public function ocrAsync(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:10240', // 10MB
        ]);

        try {
            $file = $request->file('file');
            $jobId = (string) Str::uuid();
            $storedPath = $file->store('ocr-jobs');

            Cache::put(
                ProcessOcrJob::cacheKey($jobId),
                [
                    'success' => true,
                    'async' => true,
                    'job_id' => $jobId,
                    'status' => 'queued',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
                now()->addMinutes(30)
            );

            ProcessOcrJob::dispatch(
                $jobId,
                $storedPath,
                $file->getClientOriginalName()
            )->onQueue('ocr');

            return response()->json([
                'success' => true,
                'async' => true,
                'job_id' => $jobId,
                'status' => 'queued',
            ], 202);
        } catch (Throwable $e) {
            Log::error('OCR async API error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'OCR async processing failed',
            ], 500);
        }
    }

    public function ocrStatus(string $jobId): JsonResponse
    {
        $state = Cache::get(ProcessOcrJob::cacheKey($jobId));

        if (!$state) {
            return response()->json([
                'success' => false,
                'error' => 'OCR job not found or expired',
                'job_id' => $jobId,
            ], 404);
        }

        if (($state['status'] ?? null) === 'completed' && isset($state['result']) && is_array($state['result'])) {
            return response()->json(array_merge($state, $state['result']));
        }

        return response()->json($state);
    }

    public function summarize(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'nullable|file|max:10240|required_without:text',
            'text' => 'nullable|string|required_without:file',
            'instruction' => 'nullable|string|max:500',
        ]);

        try {
            $result = $this->aiService->summarize(
                $request->file('file'),
                $request->input('text'),
                $request->input('instruction')
            );

            $status = ($result['success'] ?? false) ? 200 : 502;
            return response()->json($result, $status);
        } catch (Throwable $e) {
            Log::error('Summarize API error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Summarization failed',
            ], 500);
        }
    }
}