<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:15']
        ]);

        $bcyptPassword = bcrypt(Arr::get($incomingFields, 'password'));
        Arr::set($incomingFields, 'password', $bcyptPassword);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/');
    }

    public function logout() {
        auth()->logout();
        return redirect ('/');
    }

    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['name' => Arr::get($incomingFields, 'loginname'), 'password' => Arr::get($incomingFields, 'loginpassword')])) {
            $request->session()->regenerate();
        }

        return redirect('/');
    }
}
