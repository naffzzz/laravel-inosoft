<?php

namespace App\Applications;

use App\Infastructures\Response;
use App\Repositories\SaleRepository;
use App\Repositories\TransportationRepository;
use App\Models\Sale;

class SaleApplication
{
    // Repository
    protected $saleRepository;
    protected $transportationRepository;

    // Infrastructure
    protected $response;

    // Variables
    private $sale;
    private $request;
    private $session;
    private $isError = false;    
    private $errorMessage;

    public function __construct(
        SaleRepository $saleRepository, 
        TransportationRepository $transportationRepository,
        Response $response)
    {
        $this->saleRepository = $saleRepository;
        $this->transportationRepository = $transportationRepository;
        $this->response = $response;
    }

    public function preparation($request, $saleId = null)
    {
        if ($saleId != null)
        {
            $this->sale = $this->saleRepository->findById($saleId);
        }
        else
        {
            $this->sale = new Sale;
        }

        $this->request = $request;
        return $this;
    }

    public function create()
    {
        if ($this->isError)
        {
            return $this;
        }
        
        $this->sale->transportation_id = $this->request->transportation_id;
        $this->sale->sold = $this->request->sold;
        return $this;
    }

    public function update()
    {
        $this->sale->sold = $this->request->sold;
        return $this;
    }

    public function updateStock()
    {
        $transportation = $this->transportationRepository->findById($this->request->transportation_id);
        if ($transportation->stock < $this->request->sold)
        {
            $this->isError = true;
            $this->errorMessage = "Failed to sold data because the stock is less than sold";
        }
        $transportation->sold = $transportation->sold - $this->request->sold;
        $transportation->save();

        return $this;
    }

    public function delete()
    {
       $this->sale->delete();
       return $this;
    }

    public function execute()
    {   
        if ($this->isError)
        {
            return $this->response->responseObjectWithMessage(false, $this->errorMessage, $this->sale);
        }

        if ($this->request == null)
        {
            return $this->response->responseObject(true, $this->sale);
        }

        $execute = $this->sale->save();
        
        if ($execute) {
            return $this->response->responseObject(true, $this->sale);
        }
        return $this->response->responseObject(false, $this->sale);
    }
}

?>