<?php namespace Seiger\sMailer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Provide static access to the package-owned sMailer service.
 *
 * The facade intentionally exposes only the service binding in the foundation;
 * business methods will arrive with their corresponding application contracts.
 *
 * @mixin \Seiger\sMailer\sMailer
 * @since 2.0.0
 */
class sMailer extends Facade
{
    /**
     * Resolve the package service registered by the sMailer provider.
     *
     * @return string
     * @since 2.0.0
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sMailer';
    }
}
