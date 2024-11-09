<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\PrimaryUuidTrait;

class Url extends Model 
{
    use HasFactory, PrimaryUuidTrait;
    protected $table = 'urls';
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'id',
        'user_id',
        'link_name',
        'original_url',
        'short_url',
        'is_expire',
        'expire_date',
    ];

        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

