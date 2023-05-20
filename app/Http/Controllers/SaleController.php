<?php

namespace App\Http\Controllers;

use App\Infastructures\Response;
use App\Applications\SaleApplication;
use App\Repositories\SaleRepository;
use App\Models\Sale;
use App\Models\Transportation;
use Illuminate\Http\Request;

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
        return $this->response->successResponse("Successfully get users data", $sales);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request, $transportationId)
    {
        request()->validate([
            'transportation_id'  => 'required',
            'sold'  => 'required',
        ]);
        
        $transportations = Transportation::find($transportationId);
        $transportations->stock = $transportations->stock - $request->sold;
        if ($transportations->stock < $request->sold)
        {
            return response()->json([
                "message" => "Failed to sold data because the stock is less than sold",
                "data" => $transportations
            ], 422);
        }

        $transportations->save();

        $sales = Sale::create($request->all());
        return response()->json([
            "message" => "Sale has been added",
            "data" => $sales
        ],200);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($saleId)
    {
        $sale = $this->saleRepository->findById($saleId);
        return $this->response->successResponse("Successfully get sale data", $sale); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $saleId)
    {
        $update = $this->saleApplication
            ->preparation($request, $saleId)
            ->update()
            ->execute();

        if ($update->original['status'])
        {
            return $this->response->successResponse("Successfully update sale data", $update->original['data']); 
        }
        
        return $this->response->successResponse("Failed update sale data", $update->original['data']);   
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
        
        return $this->response->successResponse("Failed delete sale data", $delete->original['data']); 
    }
}