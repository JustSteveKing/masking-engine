<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Matchers;

final class PostalCode extends StringMatcher
{
    protected string $pattern = '/^(GIR 0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) [0-9][ABD-HJLNP-UW-Z]{2})$/';

    /**
     * @return string
     */
    public function mask(): string
    {
        return (string) preg_replace(
            pattern: '/^(GIR 0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) )[0-9][ABD-HJLNP-UW-Z]{2}$/',
            replacement: '$1***',
            subject: $this->input,
        );
    }
}
