<?php

namespace Andreyrafah\BvTelecomSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Andreyrafah\BvTelecomSms\BvTelecomSms
 */
class BvTelecomSms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Andreyrafah\BvTelecomSms\BvTelecomSms::class;
    }
}
