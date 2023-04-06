<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    private function getSellers() {
        $usersQuery = User::query()->where('is_seller', 1);
        return $usersQuery->paginate(10);
    }
    public function index() {
        $users = $this->getSellers();
        return view('auth.roles.form', compact('users'));
    }

    public function addRole(Request $request) {
        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            $user->is_seller = 1;
            $user->save();
            $users = $this->getSellers();
        }
        return view('auth.roles.form', compact('users'));
    }

    public function deleteRole(Request $request) {
        $user = User::where('email', $request->email)->first();
        $user->is_seller = 0;
        $user->save();
        $users = $this->getSellers();
        return view('auth.roles.form', compact('users'));
    }
}
