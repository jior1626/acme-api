<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use Illuminate\Http\Response;

use function PHPUnit\Framework\isEmpty;

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
            'nit' => 'required|unique:users,nit,'.$id,
            'firstname' => 'required',
            'lastname' => 'required',
            'surnames' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:users,phone,'.$id,
            'city' => 'required',
            'type' => 'required',
        ]);

        DB::beginTransaction();

        try {

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
                
                foreach($request->cars as $car) {
                    
                    if(!isEmpty($car["id"])) {
                        $updateCar = Car::findOrFail($car['id']);
                        $validation = Validator::make($car, [
                            'registration' => 'required|unique:cars,registration,'.  (int) $car['id'],
                            'type' => 'required',
                            'brand' => 'required',
                            'color' => 'required',
                        ]);

                        if($validation->fails()) {
                            return response()->json(["errors" => $validation->errors()])
                            ->setStatusCode(Response::HTTP_BAD_REQUEST, Response::$statusTexts[Response::HTTP_BAD_REQUEST]);
                        }

                        $updateCar->registration = $car["registration"];
                        $updateCar->type = $car["type"];
                        $updateCar->brand = $car["brand"];
                        $updateCar->color = $car["color"];
                        $user->OwnerCars()->update($updateCar);
                    } else {
                        $updateCar = new Car();
                        $updateCar->registration = $car["registration"];
                        $updateCar->type = $car["type"];
                        $updateCar->brand = $car["brand"];
                        $updateCar->color = $car["color"];
                        $user->OwnerCars()->save($updateCar);
                    }
                }
            }

            DB::commit();

            return response()->json(["message" => "¡Usuario actualizado correctamente!", "data" => $user]);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if($user->type == "owner") {
            $user->OwnerCars()->delete();
        }

        $user->delete();

        return response()->json(["message" => "¡Usuario borrado correctamente!"]);
    }
}
