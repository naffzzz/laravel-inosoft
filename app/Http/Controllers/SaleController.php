<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Transportation;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::get();
        return response()->json([
            "message" => "Successfully get sale data",
            "data" => $sales
        ],200);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function show($saleId)
    {
        $sales = Sale::find($saleId);
        return response()->json([
            "message" => "Successfully get sale data",
            "data" => $sales
        ],200);    

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $saleId)
    {
        $sales = Sale::find($saleId);
        $sales->sold = $request->sold;
        $sales->save();

        return response()->json([
            "message" => "Sale data has beed updated",
            "data" => $sales
        ],200);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($saleId)
    {
        $sales = Sale::find($saleId);
        $sales->delete();

        return response()->json([
            "message" => "Sale data has been deleted",
            "data" => $sales
        ], 200);
    }
}