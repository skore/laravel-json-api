<?php

namespace SkoreLabs\JsonApi\Concerns;

trait HasConfig
{
    /**
     * Get authorisable from the app config.
     *
     * @param  mixed|null  $key
     * @return bool|null
     */
    protected function getAuthorisableConfig($key = null)
    {
        $configArr = config('json-api.authorisable');
        $defaultConfigValue = false;

        return is_array($configArr) && $key !== null
            ? ($configArr[$key] ?? $defaultConfigValue)
            : $configArr ?? $defaultConfigValue;
    }

    /**
     * Get included key for resource includes.
     *
     * @return string
     */
    protected function getIncludedConfig()
    {
        return config('json-api.included', 'included');
    }
}
