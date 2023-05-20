<?php

namespace App\Applications;

use App\Applications\TransportationApplication;
use App\Constants\MachineTypeConstant;

class CarApplication extends TransportationApplication
{
    public function create()
    {
        $this->transportation->machine = MachineTypeConstant::Car;
        $this->transportation->year = $this->request->year;
        $this->transportation->price = $this->request->price;
        $this->transportation->color = $this->request->color;
        $this->transportation->type = $this->request->type;
        $this->transportation->passanger_capacity = $this->request->passanger_capacity;
        $this->transportation->stock = $this->request->stock;
        return $this;
    }
}

?>