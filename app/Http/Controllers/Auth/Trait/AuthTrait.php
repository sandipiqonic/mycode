<?php

namespace App\Http\Controllers\Auth\Trait;

use App\Events\Auth\UserLoginSuccess;
use App\Events\Frontend\UserRegistered;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Modules\Service\Models\SystemService;
use Modules\Commission\Models\EmployeeCommission;
use Modules\Commission\Models\Commission;

trait AuthTrait
{
    protected function loginTrait($request)
    {
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember_me;

        if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => 1], $remember)) {
            event(new UserLoginSuccess($request, auth()->user()));
            return true;
        }
        return false;
    }

    protected function registerTrait($request, $model = null)
    {

        try {
            $request->validate([
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['required', 'string', 'max:191'],
                'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
                'password' => ['required', Rules\Password::defaults()],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }

        $emailExists = User::where('email', $request->email)->exists();
        
        if ($emailExists) {
            return response()->json(['message' => 'Email is already in use.'], 422);
        }
        $arr = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'password' => Hash::make($request->password),
            'user_type' => 'user',
            'login_type' => $request->login_type,
        ];
        if (isset($model)) {
            $user = $model::create($arr);
        } else {
            $user = User::create($arr);
        }
        $usertype = $user->user_type;

        $user->assignRole($usertype);

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $user->save();


        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('config:cache');

        // event(new Registered($user));
        // event(new UserRegistered($user));

        return $user;
    }
}
