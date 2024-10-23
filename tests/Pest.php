<?php

declare(strict_types=1);

use JustSteveKing\Masking\Engine;
use JustSteveKing\Masking\Tests\PackageTestCase;

pest()->extends(PackageTestCase::class)->in(__DIR__);

function engine(array $config = []): Engine
{
    return new Engine(
        config: $config,
    );
}
