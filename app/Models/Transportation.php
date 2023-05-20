<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class Transportation extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transportation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'machine', 'suspension', 'transmission', 'year', 'price', 'color', 'passanger_capacity', 'type', 'stock'
    ];
}
