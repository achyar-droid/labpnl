<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users'],
        'lab' => ['required', 'in:iot,jaringan,cloud'],
        'password' => ['required', 'confirmed', 'min:6'],
    ]);

    User::create([
        'name' => $request->name,
        'username' => $request->username,
        'lab' => $request->lab,
        'password' => bcrypt($request->password),
    ]);

    return redirect('/auth')
        ->with('success', 'Akun berhasil dibuat. Silakan login.');
}

}
