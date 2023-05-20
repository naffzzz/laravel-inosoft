<?php

namespace App\Http\Controllers;

use Infrastructure\Response;
use App\Models\Transportation;
use Illuminate\Http\Request;

class TransportationController extends Controller
{
    public function index()
    {
        $transportation = Transportation::get();
        return Response()->success(["result" => $transportation],200);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            "message" => "Successfully get transportation data",
            "data" => $transportations
        ],200);     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($transportationId)
    {
        $transportations = Transportation::find($transportationId);

        return response()->json([
            "message" => "Successfully get transportation data",
            "data" => $transportations
        ],200);    

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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($transportationId)
    {
        $transportations = Transportation::find($transportationId);
        $transportations->delete();
        return response()->json([
            "message" => "Transportation data has been deleted",
            "data" => $transportations
        ],200); 
    }
}