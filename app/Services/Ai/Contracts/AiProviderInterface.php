<?php

namespace App\Services\Ai\Contracts;

interface AiProviderInterface
{
    public function name(): string;

    public function healthCheck(): bool;

    /**
     * @return array<string, mixed>
     */
    public function ocrFromPath(string $filePath, string $originalName): array;

    /**
     * @return array<string, mixed>
     */
    public function summarize(?string $filePath, ?string $originalName, ?string $text, ?string $instruction): array;
}
