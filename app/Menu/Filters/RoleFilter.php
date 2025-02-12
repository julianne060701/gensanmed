<?php

namespace App\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class RoleFilter implements FilterInterface
{
    protected $auth;

    public function transform($item)
    {
       if (isset($item['roles'])) {
        $userRoles = auth()->user()->roles()->pluck('name')->toArray();
        
        if (!array_intersect($item['roles'], $userRoles)) {
            return false;
        }
    }

    return $item;
    }
}
