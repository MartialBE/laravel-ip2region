<?php

namespace Martialbe\LaravelIp2region;

use Illuminate\Support\Facades\Facade as LaravelFacade;


class Facade extends LaravelFacade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ip2region';
    }


}
