<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Matchers;

final class Date extends StringMatcher
{
    protected string $pattern = '/^(0?[1-9]|1[0-2])\/(0?[1-9]|[12][0-9]|3[01])\/(19|20)\d{2}$/';

    public function mask(): string
    {
        return (string) preg_replace(
            pattern: '/^((0?[1-9]|1[0-2])\/(0?[1-9]|[12][0-9]|3[01])\/)(19|20)\d{2}$/',
            replacement: '$1****',
            subject: $this->input,
        );
    }
}
