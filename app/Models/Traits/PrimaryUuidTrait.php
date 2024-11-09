<?php
// app/Traits/PrimaryUuid.php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait PrimaryUuidTrait
{
    /**
     * Boot the trait.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->getKey())) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
