<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    private function getUsers() {
        $usersQuery = User::query();
        return $usersQuery->paginate(10);
    }
    public function index() {
        $users = $this->getUsers();
        return view('auth.roles.form', compact('users'));
    }

    public function addRole(Request $request) {
        $user = User::where('email', $request->email)->first();
        $user->is_seller = 1;
        $user->save();
        $users = $this->getUsers();
        return view('auth.roles.form', compact('users'));
    }
}
