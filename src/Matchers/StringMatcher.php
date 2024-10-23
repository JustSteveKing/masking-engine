<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Matchers;

use JustSteveKing\Masking\Contracts\MasksInput;

class StringMatcher implements MasksInput
{
    protected string $input;
    private string $pattern = '/./s';

    public function input(string $input): StringMatcher
    {
        $this->input = $input;

        return $this;
    }

    /** @return bool */
    public function match(): bool
    {
        return 1 === preg_match(
            pattern: $this->pattern,
            subject: $this->input,
        );
    }

    /** @return string */
    public function mask(): string
    {
        return (string) preg_replace(
            pattern: $this->pattern,
            replacement: '*',
            subject: $this->input,
        );
    }
}
