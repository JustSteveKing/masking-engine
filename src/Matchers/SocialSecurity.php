<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Matchers;

final class SocialSecurity extends StringMatcher
{
    protected string $pattern = '/^\d{3}-\d{2}-\d{4}$/';

    public function mask(): string
    {
        return (string) preg_replace(
            pattern: '/^(\d{3}-\d{2}-)(\d{4})$/',
            replacement: '***-**-$2',
            subject: $this->input,
        );
    }
}
