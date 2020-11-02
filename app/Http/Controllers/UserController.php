<?php

namespace App\Http\Controllers;

use App\Crypto;
use App\Http\Middleware\Role;
use App\Rating;
use App\RatingCount;
use App\User;
use App\Role as r;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //show all users (admin)
    public function index(){

        $all_users = User::all();

        return view('Users.all', compact('all_users'));
    }

    //edit page of an user

    public function edit(User $user){
        $roles = R::all();
        return view('users.edit', compact('user', 'roles'));

    }

    //updating user changes to database

    public function update(User $user){
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',

        ]);

        $user->name = request('name');
        $user->email = request('email');
        $user->role_id = request('role');

        $user->update($data);

        return redirect('users/all');
    }

}
