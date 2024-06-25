<?php

namespace Appslanka\LaravelBugFix\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Appslanka\LaravelBugFix\LaravelBugFix
 */
class LaravelBugFix extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Appslanka\LaravelBugFix\LaravelBugFix::class;
    }
}
