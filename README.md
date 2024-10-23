# Masking Engine

<!-- BADGES_START -->
[![Latest Version][badge-release]][packagist]
[![PHP Version][badge-php]][php]
![tests](https://github.com/juststeveking/masking-engine/workflows/ci/badge.svg)
[![Total Downloads][badge-downloads]][downloads]

[badge-release]: https://img.shields.io/packagist/v/juststeveking/masking-engine.svg?style=flat-square&label=release
[badge-php]: https://img.shields.io/packagist/php-v/juststeveking/masking-engine.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/juststeveking/masking-engine.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/juststeveking/masking-engine
[php]: https://php.net
[downloads]: https://packagist.org/packages/juststeveking/masking-engine
<!-- BADGES_END -->

A PHP masking engine, that allows you to quickly and easily mask data based on configured formats.

## Installation

You can install this package using composer

```shell
composer require juststeveking/masking-engine
```

## Configuration

You can publish the configuration for this package using the `php artisan vendor:publish --tag=masking-config`

This will publish the default config to `config/masking.php`, and will look like the following by default:

```php
use JustSteveKing\Masking\Matchers\StringMatcher;

return [
    'values' => [
        'password' => StringMatcher::class,
    ],
];
```

## Usage

Let's walk through the usage. We have a values array, that hold how we want to mask data in our application.

You will see that we are using a `StringMatcher` here, what this can do is obfuscate any string to a series of `*`.

For example `test` will turn into `****`. By default, this package comes with a selection of maskers/matchers that you can use - but you are also free to create your own.

- `StringMatcher` - will match or mask a standard string.
- `CreditCard` - will match or mask a credit card number from `1234-1234-1234-1234` to `****-****-****-1234`.
- `Date` - will match or mask a date from `09/09/1988` to `09/09/****`.
- `Email` - will match or mask an email address from `juststevemcd@gmail.com` to `************@gmail.com`.
- `PostalCode` - will match or mask a UK postal code from `B33 8TH` to `B33 ***`.
- `SocialSecurity` - will match or mask a Social Security Number from `123-45-6789` to `***-**-6789`.

Please note, these classes will not validate that the data is correct - for example it will not validate it is a correct credit card number or social security number.

The Masking Engine uses Regex to define a pattern both mask or match the input passed in. All Matching classes needs to either implement the `MasksInput` interface, or extend the `StringMatcher` class itself.

Let's look at the `Email` matcher as an example:

```php
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
```

We define a pattern as a property for the class, which is a simple Regex to make sure that the input is formatted correctly.
The masking method, will do a preg replace callback to take certain parts from the input and mask a specific part of the input.

In theory, you don't have to use Regex at all. You could implement something really simple such as:

```php
use JustSteveKing\Masking\Matchers\StringMatcher;

final class Dummy extends StringMatcher
{
    public function mask(): string
    {
        return '<DUMMY VALUE>';
    }
}
```

The only important part of the class, is that the `mask` method will return a string.

## Testing

This package comes will a full test suite, as well as static analysis checks:

```shell
composer test
```

or

```shell
composer stan
```

## Contributing

Feel free to use this package as suits your needs, if you would like to contribute please make sure that you are following the
coding standards in place for this library, and test coverage is kept to a high standard.

## Security

If you find any security related issues with this package, please feel free to reach out to me directly.
