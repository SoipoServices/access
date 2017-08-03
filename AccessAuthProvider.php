<?php

namespace Modules\Access\;

use App\Providers\AuthServiceProvider;

class AccessAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Access\Models\Access::class => \Modules\Access\Policies\AccessPolicy::class,
    ];
}
