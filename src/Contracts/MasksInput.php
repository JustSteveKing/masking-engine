<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Contracts;

interface MasksInput
{
    public function input(string $input): MasksInput;

    public function mask(): string;
}
