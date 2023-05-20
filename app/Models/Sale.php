<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Sale extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sale';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transportation_id', 
        'sold'
    ];
}
