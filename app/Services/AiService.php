<?php

namespace App\Services;

use App\Services\Ai\Contracts\AiProviderInterface;
use App\Services\Ai\Providers\HttpAiProvider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class AiService
{
    /**
     * @var array<string, AiProviderInterface>
     */
    protected array $providers = [];

    public function __construct()
    {
        $legacyConfig = (array) config('services.ai_service', []);
        $localConfig = (array) config('services.ai_service.local', []);
        $remoteConfig = (array) config('services.ai_service.remote', []);

        // Backward compatibility for existing single-provider config.
        if (empty($localConfig) && !empty($legacyConfig['base_url'])) {
            $localConfig = [
                'base_url' => $legacyConfig['base_url'],
                'timeout' => $legacyConfig['timeout'] ?? 180,
                'api_key' => $legacyConfig['api_key'] ?? null,
            ];
        }

        $localProvider = $this->makeHttpProvider('local', $localConfig);
        if ($localProvider !== null) {
            $this->providers['local'] = $localProvider;
        }

        $remoteProvider = $this->makeHttpProvider('remote', $remoteConfig);
        if ($remoteProvider !== null) {
            $this->providers['remote'] = $remoteProvider;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function ocr(UploadedFile $file): array
    {
        return $this->ocrFromPath($file->getRealPath(), $file->getClientOriginalName());
    }

    /**
     * @return array<string, mixed>
     */
    public function ocrFromPath(string $filePath, string $originalName = 'upload.jpg'): array
    {
        return $this->runFeature(
            'ocr',
            function (AiProviderInterface $provider) use ($filePath, $originalName): array {
                return $provider->ocrFromPath($filePath, $originalName);
            }
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function summarize(?UploadedFile $file, ?string $text, ?string $instruction): array
    {
        $filePath = $file ? $file->getRealPath() : null;
        $originalName = $file ? $file->getClientOriginalName() : null;

        return $this->runFeature(
            'summarize',
            function (AiProviderInterface $provider) use ($filePath, $originalName, $text, $instruction): array {
                return $provider->summarize($filePath, $originalName, $text, $instruction);
            }
        );
    }

    public function healthCheck(): bool
    {
        $driver = (string) config('services.ai_service.health_driver', 'local');

        if ($driver === 'auto') {
            foreach ($this->providers as $provider) {
                if ($provider->healthCheck()) {
                    return true;
                }
            }

            return false;
        }

        $provider = $this->providers[$driver] ?? null;

        return $provider ? $provider->healthCheck() : false;
    }

    protected function makeHttpProvider(string $providerName, array $providerConfig): ?AiProviderInterface
    {
        $baseUrl = isset($providerConfig['base_url']) ? trim((string) $providerConfig['base_url']) : '';
        if ($baseUrl === '') {
            return null;
        }

        $timeout = isset($providerConfig['timeout']) ? (int) $providerConfig['timeout'] : 180;
        if ($timeout <= 0) {
            $timeout = 180;
        }

        $apiKey = isset($providerConfig['api_key']) ? (string) $providerConfig['api_key'] : null;
        if ($apiKey === '') {
            $apiKey = null;
        }

        return new HttpAiProvider($providerName, $baseUrl, $timeout, $apiKey);
    }

    /**
     * @param callable(AiProviderInterface): array<string, mixed> $runner
     * @return array<string, mixed>
     */
    protected function runFeature(string $feature, callable $runner): array
    {
        $providers = $this->resolveProvidersForFeature($feature);
        if (empty($providers)) {
            return [
                'success' => false,
                'error' => "No AI provider configured for {$feature}",
                'meta' => [
                    'feature' => $feature,
                    'driver' => $this->driverForFeature($feature),
                ],
            ];
        }

        $errors = [];

        foreach ($providers as $index => $provider) {
            $result = $runner($provider);
            $result = is_array($result) ? $result : [];

            $result['meta'] = array_merge(
                [
                    'feature' => $feature,
                    'provider' => $provider->name(),
                    'driver' => $this->driverForFeature($feature),
                ],
                isset($result['meta']) && is_array($result['meta']) ? $result['meta'] : []
            );

            if ((bool) ($result['success'] ?? false)) {
                return $result;
            }

            $errors[] = [
                'provider' => $provider->name(),
                'error' => $result['error'] ?? 'Unknown error',
            ];

            $hasNextProvider = isset($providers[$index + 1]);
            if ($hasNextProvider) {
                Log::warning('AI provider failed, trying fallback provider', [
                    'feature' => $feature,
                    'provider' => $provider->name(),
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
            }
        }

        return [
            'success' => false,
            'error' => "All AI providers failed for {$feature}",
            'providers' => $errors,
            'meta' => [
                'feature' => $feature,
                'driver' => $this->driverForFeature($feature),
            ],
        ];
    }

    /**
     * @return array<int, AiProviderInterface>
     */
    protected function resolveProvidersForFeature(string $feature): array
    {
        $driver = $this->driverForFeature($feature);

        if ($driver === 'local' || $driver === 'remote') {
            $provider = $this->providers[$driver] ?? null;
            return $provider ? [$provider] : [];
        }

        // auto mode: prefer local first, then remote as fallback.
        $ordered = [];
        foreach (['local', 'remote'] as $providerName) {
            if (isset($this->providers[$providerName])) {
                $ordered[] = $this->providers[$providerName];
            }
        }

        return $ordered;
    }

    protected function driverForFeature(string $feature): string
    {
        $configKey = $feature === 'ocr' ? 'ocr_driver' : 'summarize_driver';
        $driver = strtolower((string) config("services.ai_service.{$configKey}", 'local'));

        if (!in_array($driver, ['local', 'remote', 'auto'], true)) {
            return 'local';
        }

        return $driver;
    }
}