<?php

namespace App\Http\Controllers;

use App\Constants\MachineTypeConstant;
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

    public function indexMotorcycle()
    {
        $transportations = $this->transportationRepository->indexMotorcycle();
        return $this->response->successResponse("Successfully get motorcycles data", $transportations);
    }

    public function indexCar()
    {
        $transportations = $this->transportationRepository->indexCar();
        return $this->response->successResponse("Successfully get cars data", $transportations);
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
        ]);

        // insert car
        if ($request->machine == MachineTypeConstant::Car) 
        {
            request()->validate([
                'year'  => 'required',
                'price'  => 'required',
                'color'  => 'required',
                'passanger_capacity'  => 'required',
                'type'  => 'required',
                'stock'  => 'required',
            ]);

            $transportations = $this->carApplication
                ->preparation($request)
                ->create()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update transportation data", $transportations->original['data']);
            }
        }
        else if ($request->machine == MachineTypeConstant::Motorcycle) // insert motorcycle
        {
            request()->validate([
                'machine'  => 'required',
                'suspension'  => 'required',
                'transmission'  => 'required',
                'year'  => 'required',
                'price'  => 'required',
                'color'  => 'required',
                'stock'  => 'required',
            ]);

            $transportations = $this->motorcycleApplication
                ->preparation($request)
                ->create()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update transportation data", $transportations->original['data']);
            }
        }

        return $this->response->successResponse("Failed update transportation data", $request);  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($transportationId)
    {
        $transportation = $this->transportationRepository->findById($transportationId);
        return $this->response->successResponse("Successfully get transportation data", $transportation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $transportationId)
    {
        // update car
        if ($request->machine == MachineTypeConstant::Car) 
        {
            $transportations = $this->carApplication
                ->preparation($request, $transportationId)
                ->update()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update car data", $transportations->original['data']);
            }
        }
        else if ($request->machine == MachineTypeConstant::Motorcycle) // update motorcycle
        {
            $transportations = $this->motorcycleApplication
                ->preparation($request, $transportationId)
                ->update()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update motorcycle data", $transportations->original['data']);
            }
        }

        return $this->response->successResponse("Failed update transportation data", $request);    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function updateStock(Request $request, $transportationId)
    {
        // update car
        if ($request->machine == MachineTypeConstant::Car) 
        {
            $transportations = $this->carApplication
                ->preparation($request, $transportationId)
                ->updateStock()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update car data", $transportations->original['data']);
            }
        }
        else if ($request->machine == MachineTypeConstant::Motorcycle) // update motorcycle
        {
            $transportations = $this->motorcycleApplication
                ->preparation($request, $transportationId)
                ->updateStock()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update motorcycle data", $transportations->original['data']);
            }
        }

        return $this->response->successResponse("Failed update transportation data", $request); 
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