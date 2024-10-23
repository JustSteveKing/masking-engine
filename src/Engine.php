<?php

declare(strict_types=1);

namespace JustSteveKing\Masking;

use JustSteveKing\Masking\Contracts\MaskingEngine;
use JustSteveKing\Masking\Contracts\MasksInput;

final readonly class Engine implements MaskingEngine
{
    /** @param array<string,class-string> $config */
    public function __construct(
        public array $config = [],
    ) {}

    public function mask(array $payload): array
    {
        return $this->maskArray($payload, []);
    }

    private function maskArray(array $data, array $path): array
    {
        foreach ($data as $key => $value) {
            $currentPath = array_merge($path, [$key]);
            $dotNotationKey = implode('.', $currentPath);

            if ($this->shouldMask($dotNotationKey)) {
                /** @var MasksInput $maskerClass */
                $maskerClass = $this->getMaskerClass($dotNotationKey);
                if (is_array($value)) {
                    $data[$key] = $this->maskArray($value, $currentPath);
                } else {
                    $data[$key] = (new $maskerClass())->input($value)->mask();
                }
            } elseif (is_array($value)) {
                $data[$key] = $this->maskArray($value, $currentPath);
            }
        }

        return $data;
    }

    private function shouldMask(string $dotNotationKey): bool
    {
        foreach ($this->config as $pattern => $maskerClass) {
            $regex = $this->convertPatternToRegex($pattern);
            if (preg_match($regex, $dotNotationKey)) {
                return true;
            }
        }

        return false;
    }

    private function getMaskerClass(string $dotNotationKey): string
    {
        foreach ($this->config as $pattern => $maskerClass) {
            $regex = $this->convertPatternToRegex($pattern);
            if (preg_match($regex, $dotNotationKey)) {
                return "{$maskerClass}";
            }
        }

        return '';
    }

    private function convertPatternToRegex(string $pattern): string
    {
        $escapedPattern = preg_quote($pattern, '/');
        $regex = str_replace(['\*', '\.\*'], ['[^.]+', '.*'], $escapedPattern);

        return '/^' . $regex . '$/';
    }
}
