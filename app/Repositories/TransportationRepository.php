<?php

namespace App\Repositories;

use App\Models\Transportation;

class TransportationRepository
{
    public function index()
    {
        return Transportation::get();
    }

    public function findById($userId)
    {
        return Transportation::find($userId);
    }
}

?>