<?php

namespace App\Http\Controllers;

use App\Infastructures\Response;
use App\Applications\SaleApplication;
use App\Repositories\SaleRepository;
use App\Validations\SaleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    protected $saleApplication;
    protected $saleRepository;
    protected $response;

    public function __construct(
        SaleApplication $saleApplication,
        SaleRepository $saleRepository,
        Response $response)
    {
        $this->saleApplication = $saleApplication;
        $this->saleRepository = $saleRepository;
        $this->response = $response;
    }

    public function index()
    {
        $sales = $this->saleRepository->index();        
        if ($sales)
        {
            return $this->response->successResponse("Successfully get sales data", $sales);
        }
        return $this->response->errorResponse("Sales data not found");
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request, $transportationId)
    {
        //set validation
        $validator = Validator::make($request->all(), SaleValidation::saleRule);

        //if validation fails
        if ($validator->fails()) {
            return $this->response->validationResponse($validator->errors());
        }

        $sales = $this->saleApplication
                ->preparation($request)
                ->updateStock()
                ->create()
                ->execute();

        if ($sales->original['status']) {
            return $this->response->successResponse("Successfully add sale data", $sales->original['data']);
        }
        else if (isset($sales->original['message']))
        {
            return $this->response->errorResponse($sales->original['message']);  
        }

        return $this->response->errorResponse("Failed add sale data");  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($saleId)
    {
        $sale = $this->saleRepository->findById($saleId);
        if ($sale)
        {
            return $this->response->successResponse("Successfully get sale data", $sale); 
        }
        return $this->response->errorResponse("Sale data not found");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $saleId)
    {
        //set validation
        $validator = Validator::make($request->all(), SaleValidation::updateRule);

        //if validation fails
        if ($validator->fails()) {
            return $this->response->validationResponse($validator->errors());
        }

        $update = $this->saleApplication
            ->preparation($request, $saleId)
            ->update()
            ->execute();

        if ($update->original['status'])
        {
            return $this->response->successResponse("Successfully update sale data", $update->original['data']); 
        }
        
        return $this->response->errorResponse("Failed update sale data");   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($saleId)
    {
        $delete = $this->saleApplication
            ->preparation(null, $saleId)
            ->delete()
            ->execute();

        if ($delete->original['status'])
        {
            return $this->response->successResponse("Successfully delete sale data", $delete->original['data']); 
        }
        
        return $this->response->errorResponse("Failed delete sale data"); 
    }
}