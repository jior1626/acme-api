<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $users = User::all();

        if($request->get("type")) {
            $users = $users->filter(function ($user) use ($request) {
                return $user->type == $request->get("type");
            });
        }
       
        return response()->json($users);
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
            'nit' => 'required|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
            'surnames' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:users',
            'city' => 'required',
            'type' => 'required',
        ]);

       DB::beginTransaction();

        try {

            $newUser = new User();
            $newUser->nit = $request->nit;
            $newUser->firstname = $request->firstname;
            $newUser->lastname = $request->lastname;
            $newUser->surnames = $request->surnames;
            $newUser->address = $request->address;
            $newUser->phone = $request->phone;
            $newUser->city = $request->city;
            $newUser->type = $request->type;
            $newUser->save();
    
            if($request->type == "owner") {
                
                foreach($request->cars as $item) {
    
                    $validation = Validator::make($item, [
                        'registration' => 'required|unique:cars',
                        'type' => 'required',
                        'brand' => 'required',
                        'color' => 'required',
                    ]);

                    if($validation->fails()) {
                        return response()->json(["errors" => $validation->errors()])
                        ->setStatusCode(Response::HTTP_BAD_REQUEST, Response::$statusTexts[Response::HTTP_BAD_REQUEST]);
                    }
    
                    $newCar = new Car();
                    $newCar->registration = $item["registration"];
                    $newCar->type = $item["type"];
                    $newCar->brand = $item["brand"];
                    $newCar->color = $item["color"];
                    $newUser->OwnerCars()->save($newCar);
                }
            }
            
           DB::commit();
            
            return response()->json(["message" => "¡Usuario creado correctamente!", "data" => $newUser]);

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
       
        return response()->json($user);
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
            'nit' => 'required|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
            'surnames' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:users',
            'city' => 'required',
            'type' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->nit = $request->nit;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->surnames = $request->surnames;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->type = $request->type;
        $user->save();

        if($request->type == "owner") {

            $request->cars->foreach(function ($item) use ($user) {
                $car = new Car();
                if($item->id) {
                    $car = Car::findOrFail($item->id);
                }
                $car->registration = $item->registration;
                $car->type = $item->type_car;
                $car->brand = $item->brand;
                $car->color = $item->color;
                $user->OwnerCars()->save($car);
            });
        }

        return response()->json(["message" => "¡Usuario actualizado correctamente!", "data" => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(["message" => "¡Usuario borrado correctamente!"]);
    }
}
