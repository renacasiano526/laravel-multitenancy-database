<?php


namespace Spatie\Multitenancy\Models\Concerns;


trait UsesTenantConnection
{
    public function getConnectionName()
    {
        return 'tenant';
    }
}
