<?php

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository
{
    public function index()
    {
        return Sale::get();
    }

    public function findById($userId)
    {
        return Sale::find($userId);
    }
}

?>