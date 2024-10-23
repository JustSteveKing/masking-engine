<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Matchers;

final class Email extends StringMatcher
{
    protected string $pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';

    public function mask(): string
    {
        return (string) preg_replace_callback(
            pattern: '/([^@]+)/',
            callback: static fn(array $matches): string => str_repeat(
                string: '*',
                times: mb_strlen($matches[0]),
            ),
            subject: $this->input,
            limit: 1,
        );
    }
}
