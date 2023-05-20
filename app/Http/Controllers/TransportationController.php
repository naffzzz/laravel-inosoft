<?php

namespace App\Http\Controllers;

use App\Infastructures\Response;
use App\Applications\CarApplication;
use App\Applications\MotorcycleApplication;
use App\Applications\TransportationApplication;
use App\Repositories\TransportationRepository;
use App\Models\Transportation;
use Illuminate\Http\Request;

class TransportationController extends Controller
{
    protected $carApplication;
    protected $motorcycleApplication;
    protected $transportationApplication;
    protected $transportationRepository;
    protected $response;
    public function __construct(
        CarApplication $carApplication,
        MotorcycleApplication $motorcycleApplication,
        TransportationApplication $transportationApplication,
        TransportationRepository $transportationRepository,
        Response $response)
    {
        $this->carApplication = $carApplication;
        $this->motorcycleApplication = $motorcycleApplication;
        $this->transportationApplication = $transportationApplication;
        $this->transportationRepository = $transportationRepository;
        $this->response = $response;
    }
    public function index()
    {
        $transportations = $this->transportationRepository->index();
        return $this->response->successResponse("Successfully get transporations data", $transportations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        request()->validate([
            'machine'  => 'required',
            'suspension'  => 'required',
            'transmission'  => 'required',
            'year'  => 'required',
            'price'  => 'required',
            'color'  => 'required',
            'passanger_capacity'  => 'required',
            'stock'  => 'required',
        ]);

        $transportations = Transportation::create($request->all());

        return response()->json([
            "message" => "Successfully add transportation data",
            "data" => $transportations
        ],200);     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($transportationId)
    {
        $transporation = $this->transportationRepository->findById($transportationId);
        return $this->response->successResponse("Successfully get transportation data", $transporation);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $transportationId)
    {
        $transportations = Transportation::find($transportationId);
        $transportations->machine = $request->machine;
        $transportations->suspension = $request->suspension;
        $transportations->transmission = $request->transmission;
        $transportations->year = $request->year;
        $transportations->price = $request->price;
        $transportations->color = $request->color;
        $transportations->passanger_capacity = $request->passanger_capacity;
        $transportations->save();

        return response()->json([
            "message" => "Transportation data has been updated",
            "data" => $transportations
        ],200);    
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function updateStock(Request $request, $transportationId)
    {
        $transportations = Transportation::find($transportationId);
        $transportations->stock = $transportations->stock + $request->stock;
        $transportations->save();

        return response()->json([
            "message" => "Transportation stock data has been updated",
            "data" => $transportations
        ],200);    
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($transportationId)
    {
        $delete = $this->transportationApplication
            ->preparation(null, $transportationId)
            ->delete()
            ->execute();

        if ($delete->original['status'])
        {
            return $this->response->successResponse("Successfully delete transportation data", $delete->original['data']); 
        }
        
        return $this->response->successResponse("Failed delete transportation data", $delete->original['data']); 
    }
}