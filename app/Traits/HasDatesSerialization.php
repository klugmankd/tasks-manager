<?php

namespace App\Traits;

trait HasDatesSerialization
{

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i');
    }
}
