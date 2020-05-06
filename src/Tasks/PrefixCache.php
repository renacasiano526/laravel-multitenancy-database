<?php

namespace Spatie\Multitenancy\Tasks;

use Spatie\Multitenancy\Exceptions\TaskCannotBeExecuted;
use Spatie\Multitenancy\Models\Tenant;

class PrefixCache implements MakeTenantCurrentTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        $storeName = config('cache.default');

        $this->ensureCacheStoreSupportsPrefix($storeName);

        config()->set('cache.prefix', "tenant_id_{$tenant->id}");

        app('cache')->forgetDriver($storeName);
    }

    protected function ensureCacheStoreSupportsPrefix(string $storeName): void
    {
        $supportedDrivers = ['memcached', 'redis'];

        $driver = config("cache.stores.{$storeName}.driver");

        if (! in_array($driver, $supportedDrivers)) {
            throw TaskCannotBeExecuted::make($this, "tasks only supports " . implode(', ', $supportedDrivers) . " cache drivers. Used driver: `{$driver}`");
        }
    }
}
