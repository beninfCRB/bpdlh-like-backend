<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProviderInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpAiProvider implements AiProviderInterface
{
    protected string $providerName;
    protected string $baseUrl;
    protected int $timeout;
    protected ?string $apiKey;

    public function __construct(string $providerName, string $baseUrl, int $timeout, ?string $apiKey)
    {
        $this->providerName = $providerName;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->apiKey = $apiKey;
    }

    public function name(): string
    {
        return $this->providerName;
    }

    public function healthCheck(): bool
    {
        try {
            $response = $this->client(5)->get("{$this->baseUrl}/health");

            return $response->successful() && ($response->json('status') === 'healthy');
        } catch (\Throwable $e) {
            Log::warning('AI provider health check failed', [
                'provider' => $this->providerName,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function ocrFromPath(string $filePath, string $originalName): array
    {
        Log::info('AI OCR request', [
            'provider' => $this->providerName,
            'file' => $originalName,
        ]);

        try {
            $response = $this->client()
                ->attach('file', file_get_contents($filePath), $originalName)
                ->post("{$this->baseUrl}/api/ocr");

            return $response->json() ?: [];
        } catch (\Throwable $e) {
            Log::error('AI OCR failed', [
                'provider' => $this->providerName,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function summarize(?string $filePath, ?string $originalName, ?string $text, ?string $instruction): array
    {
        Log::info('AI summarize request', [
            'provider' => $this->providerName,
            'has_file' => $filePath !== null,
        ]);

        try {
            $payload = [
                'instruction' => $instruction ?: 'Ringkas dalam Bahasa Indonesia.',
            ];

            if ($filePath !== null && $originalName !== null) {
                $response = $this->client()
                    ->attach('file', file_get_contents($filePath), $originalName)
                    ->post("{$this->baseUrl}/api/summarize", $payload);
            } else {
                $payload['text'] = $text;
                $response = $this->client()->post("{$this->baseUrl}/api/summarize", $payload);
            }

            return $response->json() ?: [];
        } catch (\Throwable $e) {
            Log::error('AI summarize failed', [
                'provider' => $this->providerName,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function client(?int $timeout = null): PendingRequest
    {
        $request = Http::acceptJson()->timeout($timeout ?? $this->timeout);

        if (!empty($this->apiKey)) {
            $request = $request->withHeaders([
                'X-API-Key' => $this->apiKey,
            ]);
        }

        return $request;
    }
}
