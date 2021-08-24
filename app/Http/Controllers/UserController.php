<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return $user;
    }

    public function updateUserLevel(Request $request, $id)
    {
       
        $user = User::findOrFail($id);

        $user->update(['user_level' => $request->user_level]);

        return $user;
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function storeRandom($count)
    {   
        $seeder = new \Database\Seeders\UserSeeder();

        $seeder->run($count);
        
        return User::all();
    }

    public function storeAdminRandom($count)
    {   
        $seeder = new \Database\Seeders\SuperAdminSeeder();

        $seeder->run($count);
        
        return User::all();
    }
}