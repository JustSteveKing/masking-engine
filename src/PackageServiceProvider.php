<?php

declare(strict_types=1);

namespace JustSteveKing\Masking;

use Illuminate\Support\ServiceProvider;

final class PackageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/masking.php' => config_path('masking.php'),
        ], 'masking-config');
    }
}
