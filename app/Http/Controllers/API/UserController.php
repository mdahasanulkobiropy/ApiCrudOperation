<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResouce;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::latest()->get();
        $users = User::whereJsonContains('address->city', 'dhaka')->get();
        // dd($users);
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No User found'], 200);
        }       
        return UserResouce::collection($users);
        
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
        // dd($request->all());
        try{
            $user = User::create($request->except('_token','address'));
            $user->address = json_encode($request->address);
            $user->save();
            return response()->json([
                'message' => 'User created successfully',
                'data' => new UserResouce($user),
            ],200);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'An error occured :' . $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
