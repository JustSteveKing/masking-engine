<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Contracts;

interface MaskingEngine
{
    public function mask(array $payload): array;
}
