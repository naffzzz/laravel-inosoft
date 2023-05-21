<?php

namespace App\Applications;

use App\Infastructures\Response;
use App\Repositories\TransportationRepository;
use App\Models\Transportation;

class TransportationApplication
{
    // Repository
    protected $transportationRepository;

    // Infrastructure
    protected $response;

    // Variables
    protected $transportation;
    protected $request;
    protected $session;

    public function __construct(TransportationRepository $transportationRepository, Response $response)
    {
        $this->transportationRepository = $transportationRepository;
        $this->response = $response;
    }

    public function preparation($request, $transportationId = null)
    {
        if ($transportationId != null)
        {
            $this->transportation = $this->transportationRepository->findById($transportationId);
        }
        else
        {
            $this->transportation = new Transportation;
        }

        $this->request = $request;
        return $this;
    }

    public function delete()
    {
       $this->transportation->delete();
       return $this;
    }


    public function execute()
    {   
        if ($this->request == null)
        {
            return $this->response->responseObject(true, $this->transportation);
        }

        $execute = $this->transportation->save();
        
        if ($execute) {
            return $this->response->responseObject(true, $this->transportation);
        }
        return $this->response->responseObject(false, $this->transportation);
    }
}

?>