<?php

namespace App\Support;

use Illuminate\Support\Collection as BaseCollection;


class Collection extends BaseCollection
{
    #[\ReturnTypeWillChange]
    public function offsetExists($key)
    {
        return parent::offsetExists($key);
    }
}