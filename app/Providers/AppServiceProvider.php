<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * App-wide defaults (strict models, immutable dates, destructive-command guard,
 * production password rules...) come from nunomaduro/essentials.
 */
final class AppServiceProvider extends ServiceProvider {}
