<?php

declare(strict_types=1);

use JustSteveKing\Masking\Engine;
use JustSteveKing\Masking\Matchers\CreditCard;
use JustSteveKing\Masking\Matchers\Date;
use JustSteveKing\Masking\Matchers\Email;
use JustSteveKing\Masking\Matchers\PostalCode;
use JustSteveKing\Masking\Matchers\SocialSecurity;
use JustSteveKing\Masking\Matchers\StringMatcher;

beforeEach(function (): void {
    $this->engine = new Engine(
        config: [
            'dob' => Date::class,
            'cc' => CreditCard::class,
            'email' => Email::class,
            'password' => StringMatcher::class,
            'user.email' => Email::class,
            'account.*' => StringMatcher::class,
            'account.*.email' => Email::class,
            'ss' => SocialSecurity::class,
            'user.password' => StringMatcher::class,
            'postal_code' => PostalCode::class,
        ],
    );
});

test('it can create a new masking engine', function (): void {
    expect(
        $this->engine->config,
    )->toBeArray();
});

test('it can mask a standard string', function (): void {
    expect(
        $this->engine->mask(['password' => 'password']),
    )->toEqual(['password' => '********']);
});

test('it can mask an nested value', function (): void {
    expect(
        $this->engine->mask(['user' => ['password' => 'password']]),
    )->toEqual(['user' => ['password' => '********']]);
});

test('it can mask a date', function (): void {
    expect(
        $this->engine->mask(['dob' => '09/09/1988']),
    )->toEqual(['dob' => '09/09/****']);
});

test('it can mask a credit card', function (): void {
    expect(
        $this->engine->mask(['cc' => '1234-1234-1234-1234']),
    )->toEqual(['cc' => '****-****-****-1234']);
});

test('it can mask an email address', function (): void {
    expect(
        $this->engine->mask(['email' => 'test@example.com']),
    )->toEqual(['email' => '****@example.com']);
});

test('it can mask a postal code', function (): void {
    expect(
        $this->engine->mask(['postal_code' => 'B33 8TH']),
    )->toEqual(['postal_code' => 'B33 ***']);
});

test('it can mask a social security number', function (): void {
    expect(
        $this->engine->mask(['ss' => '123-45-6789']),
    )->toEqual(['ss' => '***-**-6789']);
});

test('it can mask wildcard values', function (): void {
    $this->assertEquals(
        expected: [
            'account' => [
                'name' => '*************',
                'user' => [
                    'email' => '************@gmail.com',
                ],
            ],
        ],
        actual: $this->engine->mask(
            payload: [
                'account' => [
                    'name' => 'JustSteveKing',
                    'user' => [
                        'email' => 'juststevemcd@gmail.com',
                    ],
                ],
            ],
        ),
    );
});

test('it can match a string', function (): void {
    expect(
        (new StringMatcher())->input('test')->match(),
    )->toBeBool()->toEqual(true);
});

test('it can match a credit card', function (): void {
    expect(
        (new CreditCard())->input('1234-1234-1234-1234')->match(),
    )->toBeBool()->toEqual(true);
});

test('it can match a date', function (): void {
    expect(
        (new Date())->input('09/09/1988')->match(),
    )->toBeBool()->toEqual(true);
});

test('it can match an email address', function (): void {
    expect(
        (new Email())->input('juststevemcd@gmail.com')->match(),
    )->toBeBool()->toEqual(true);
});

test('it can match a postal code', function (): void {
    expect(
        (new PostalCode())->input('B33 8TH')->match(),
    )->toBeBool()->toEqual(true);
});

test('it can match a social security number', function (): void {
    expect(
        (new SocialSecurity())->input('123-45-6789')->match(),
    )->toBeBool()->toEqual(true);
});

test('it will return the original number if credit card is not formatted correctly', function (): void {
    expect(
        $this->engine->mask(['cc' => '1234-1234-1234']),
    )->toEqual(['cc' => '1234-1234-1234']);
});
