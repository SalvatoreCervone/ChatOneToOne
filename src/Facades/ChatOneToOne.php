<?php

namespace SalvatoreCervone\ChatOneToOne\Facades;

use Illuminate\Support\Facades\Facade;

class ChatOneToOne extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'chatonetoone';
    }
}
