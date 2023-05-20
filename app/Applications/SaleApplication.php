<?php

namespace App\Applications;

use App\Infastructures\Response;
use App\Repositories\SaleRepository;
use App\Models\Sale;

class SaleApplication
{
    // Repository
    protected $saleRepository;

    // Infrastructure
    protected $response;

    // Variables
    private $sale;
    private $request;
    private $session;

    public function __construct(SaleRepository $saleRepository, Response $response)
    {
        $this->saleRepository = $saleRepository;
        $this->response = $response;
    }

    public function preparation($request, $userId = null)
    {
        if ($userId != null)
        {
            $this->sale = $this->saleRepository->findById($userId);
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
        $this->sale->transportation_id = $this->request->transportation_id;
        $this->sale->sold = $this->request->sold;
        return $this;
    }

    public function update()
    {
        $this->sale->sold = $this->request->sold;
        return $this;
    }

    public function delete()
    {
       $this->sale->delete();
       return $this;
    }

    public function execute()
    {   
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