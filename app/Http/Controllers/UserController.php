<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = "";
        if($request->get("type")) {
            $type = $request->get("type");
        }

        $users = User::where("type", $type)->get();
       
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

        return response()->json(["message" => "¡Usuario creado correctamente!", "data" => $newUser]);
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

        return response()->json(["message" => "¡Usuario creado correctamente!", "data" => $user]);
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
