<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]); 
        /** @var User $user */
        $user = User::where('email',$request->get('email'))->first();

        if (!$user) {
            throw ValidationException::withMessages([
              'email' => 'El email no existe o no coincide con los datos',
            ]);            
          }
          
          if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
              'email' => 'El email no existe o no coincide con los datos',
            ]);            
          }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }
}
