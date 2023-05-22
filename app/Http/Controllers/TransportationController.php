<?php

namespace App\Http\Controllers;

use App\Constants\MachineTypeConstant;
use App\Infastructures\Response;
use App\Applications\CarApplication;
use App\Applications\MotorcycleApplication;
use App\Applications\TransportationApplication;
use App\Repositories\TransportationRepository;
use App\Models\Transportation;
use App\Validations\TransportationValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if ($transportations)
        {
            return $this->response->successResponse("Successfully get transporations data", $transportations);
        }
        return $this->response->errorResponse("Transportations data not found");
    }

    public function indexMotorcycle()
    {
        $transportations = $this->transportationRepository->indexMotorcycle();
        if ($transportations)
        {
            return $this->response->successResponse("Successfully get motorcycles data", $transportations);
        }
        return $this->response->errorResponse("Motorcycles data not found");
    }

    public function indexCar()
    {
        $transportations = $this->transportationRepository->indexCar();
        if ($transportations)
        {
            return $this->response->successResponse("Successfully get cars data", $transportations);
        }
        return $this->response->errorResponse("Cars data not found");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), TransportationValidation::transportationRule);

        //if validation fails
        if ($validator->fails()) {
            return $this->response->validationResponse($validator->errors());
        }

        // insert car
        if ($request->machine == MachineTypeConstant::Car) 
        {
            //set validation
            $validator = Validator::make($request->all(), TransportationValidation::carRule);

            //if validation fails
            if ($validator->fails()) {
                return $this->response->validationResponse($validator->errors());
            }

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
            //set validation
            $validator = Validator::make($request->all(), TransportationValidation::motorcycleRule);

            //if validation fails
            if ($validator->fails()) {
                return $this->response->validationResponse($validator->errors());
            }

            $transportations = $this->motorcycleApplication
                ->preparation($request)
                ->create()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update transportation data", $transportations->original['data']);
            }
        }

        return $this->response->errorResponse("Failed update transportation data");  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($transportationId)
    {
        $transportation = $this->transportationRepository->findById($transportationId);
        
        if ($transportation)
        {
            return $this->response->successResponse("Successfully get transportation data", $transportation);
        }
        return $this->response->errorResponse("Cars data not found");
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
            //set validation
            $validator = Validator::make($request->all(), TransportationValidation::carRule);

            //if validation fails
            if ($validator->fails()) {
                return $this->response->validationResponse($validator->errors());
            }

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
            //set validation
            $validator = Validator::make($request->all(), TransportationValidation::motorcycleRule);

            //if validation fails
            if ($validator->fails()) {
                return $this->response->validationResponse($validator->errors());
            }

            $transportations = $this->motorcycleApplication
                ->preparation($request, $transportationId)
                ->update()
                ->execute();

            if ($transportations->original['status']) {
                return $this->response->successResponse("Successfully update motorcycle data", $transportations->original['data']);
            }
        }

        return $this->response->errorResponse("Failed update transportation data");    
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

        return $this->response->errorResponse("Failed update transportation data"); 
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
        
        return $this->response->errorResponse("Failed delete transportation data"); 
    }
}