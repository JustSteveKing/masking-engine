<?php

declare(strict_types=1);

namespace JustSteveKing\Masking\Matchers;

final class CreditCard extends StringMatcher
{
    protected string $pattern = '/\d{4}-?\d{4}-?\d{4}-?\d{4}/';

    public function mask(): string
    {
        $sanitizedCard = preg_replace(
            pattern: '/\D/',
            replacement: '',
            subject: $this->input,
        );

        // If the result isn't 16 digits long, return original
        if (null === $sanitizedCard || 16 !== mb_strlen($sanitizedCard)) {
            return $this->input;
        }

        // Return the masked card
        return "****-****-****-" . mb_substr($sanitizedCard, -4);
    }
}
