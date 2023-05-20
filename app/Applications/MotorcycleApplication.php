<?php

namespace App\Applications;

use App\Applications\TransportationApplication;
use App\Constants\MachineTypeConstant;

class MotorcycleApplication extends TransportationApplication
{
    public function create()
    {
        $this->transportation->machine = MachineTypeConstant::Motorcycle;
        $this->transportation->suspension = $this->request->suspension;
        $this->transportation->transmission = $this->request->transmission;
        $this->transportation->year = $this->request->year;
        $this->transportation->price = $this->request->price;
        $this->transportation->color = $this->request->color;
        $this->transportation->stock = $this->request->stock;
        return $this;
    }
}

?>