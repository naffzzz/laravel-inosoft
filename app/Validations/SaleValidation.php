<?php 

namespace App\Validations;

class SaleValidation
{
    public const saleRule = [
        'transportation_id'  => 'required',
        'sold'  => 'required',
    ];

    public const updateRule = [
        'transportation_id'  => 'required',
        'sold'  => 'required',
    ];
}

?>