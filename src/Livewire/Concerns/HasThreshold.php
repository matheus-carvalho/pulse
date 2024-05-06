<?php

namespace Laravel\Pulse\Livewire\Concerns;

use Illuminate\Support\Facades\Config;

trait HasThreshold
{
    /**
     * Get the threshold for the given value.
     *
     * @param  class-string  $recorder
     */
    public function threshold(string $value, string $recorder = self::class): int
    {
        // TODO: delete this
        $config = Config::get('pulse.recorders.'.$recorder.'.threshold');

        if (! is_array($config)) {
            return $config;
        }

        // @phpstan-ignore argument.templateType, argument.templateType
        $custom = collect($config)
            ->except(['default'])
            ->first(fn ($threshold, $pattern) => preg_match($pattern, $value));

        return $custom ?? $config['default'];
    }
}
