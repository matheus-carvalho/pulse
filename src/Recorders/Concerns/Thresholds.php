<?php

namespace Laravel\Pulse\Recorders\Concerns;

use Illuminate\Support\Facades\Config;

trait Thresholds
{
    /**
     * Determine if the duration is under the configured threshold.
     */
    protected function underThreshold(int|float $duration, string $value): bool
    {
        return $duration < $this->threshold($value);
    }

    /**
     * Get the threshold for the given value.
     */
    protected function threshold(string $value, ?string $recorder = null): int
    {
        $recorder ??= static::class;

        $config = Config::get("pulse.recorders.{$recorder}.threshold");

        if (! is_array($config)) {
            return $config;
        }

        // @phpstan-ignore argument.templateType, argument.templateType
        $custom = collect($config)
            ->except(['default'])
            ->first(fn ($threshold, $pattern) => preg_match($pattern, $value));

        // TODO: cannot have a `default` named route, job, etc., here
        return $custom ?? $config['default'] ?? 1_000;
    }
}
