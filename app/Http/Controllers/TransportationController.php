<?php

namespace App\Http\Controllers;

use App\Models\Transportation;
use Illuminate\Http\Request;

class TransportationController extends Controller
{
    public function index()
    {
        $transportation = Transportation::get();
        return response()->json(["result" => $transportation],200);    
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
        ]);

        Transportation::create($request->all());
        return response()->json(["result" => "Transportation has been added"],200);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($transportationId)
    {
        $transportation = Transportation::find($transportationId);
        return response()->json(["result" => $transportation],200);    

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
        $transportation = Transportation::find($transportationId);
        $transportation->machine = $request->machine;
        $transportation->suspension = $request->suspension;
        $transportation->transmission = $request->transmission;
        $transportation->year = $request->year;
        $transportation->price = $request->price;
        $transportation->color = $request->color;
        $transportation->passanger_capacity = $request->passanger_capacity;
        $transportation->save();

        return response()->json(["result" => "Transportation has been updated"],201);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($transportationId)
    {
        $transportation = Transportation::find($transportationId);
        $transportation->delete();

        return response()->json(["result" => "Transportation has been deleted"], 200);
    }
}