<?php 

namespace App\Validations;

class TransportationValidation
{

    public const transportationRule = [
        'machine'  => 'required'
    ];

    public const carRule = [
        'year'  => 'required',
        'price'  => 'required',
        'color'  => 'required',
        'passanger_capacity'  => 'required',
        'type'  => 'required',
        'stock'  => 'required'
    ];

    public const motorcycleRule = [
        'machine'  => 'required',
        'suspension'  => 'required',
        'transmission'  => 'required',
        'year'  => 'required',
        'price'  => 'required',
        'color'  => 'required',
        'stock'  => 'required'
    ];
}

?>