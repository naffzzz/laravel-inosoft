<?php

namespace App\Repositories;

use App\Constants\MachineTypeConstant;
use App\Models\Transportation;

class TransportationRepository
{
    public function index()
    {
        return Transportation::get();
    }

    public function indexMotorcycle()
    {
        return Transportation::where('machine',MachineTypeConstant::Motorcycle)->get();
    }

    public function indexCar()
    {
        return Transportation::where('machine',MachineTypeConstant::Car)->get();
    }

    public function findById($userId)
    {
        return Transportation::find($userId);
    }
}

?>