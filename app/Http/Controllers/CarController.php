<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cars = Car::all();

        if($request->get("ownerId")) {
            $cars = $cars->filter( function($car) use ($request) {
                return $car->ownerId == $request->ownerId;
            });  
        }

        if($request->get("driverId")) {
            $cars = $cars->filter( function($car) use ($request) {
                return $car->driverId == $request->driverId;
            });  
        }

        return response()->json($cars);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'registration' => 'required|unique:cars',
            'color' => 'required',
            'brand' => 'required',
            'type' => 'required',
            'owner_id' => 'required',
            'driver_id' => 'required',
        ]);

        $car = new Car();
        $car->registration = $request->registration;
        $car->color = $request->color;
        $car->brand = $request->brand;
        $car->type = $request->type;
        $car->owner_id = $request->owner_id;
        $car->driver_id = $request->driver_id;
        $car->save();

        return response()->json(["message" => "¡Vehiculo creado correctamente!", "data" => $car]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::findOrFail($id);

        return response()->json($car);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'registration' => 'required|unique:cars',
            'color' => 'required',
            'brand' => 'required',
            'type' => 'required',
            'owner_id' => 'required',
            'driver_id' => 'required',
        ]);

        $car = Car::findOrFail($id);

        $car->registration = $request->registration;
        $car->color = $request->color;
        $car->brand = $request->brand;
        $car->type = $request->type;
        $car->owner_id = $request->owner_id;
        $car->driver_id = $request->driver_id;
        $car->save();

        return response()->json(["message" => "¡Vehiculo actualizado correctamente!", "data" => $car]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);

        $car->delete();

        return response()->json(["message" => "¡Vehiculo borrado correctamente!"]);
    }
}
