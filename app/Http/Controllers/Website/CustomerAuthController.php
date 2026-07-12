<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $phone = Phone::normalizeBd($request->input('phone'));
        $request->merge(['phone' => $phone]);

        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'regex:/^0\d{10}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'phone.required' => 'Enter an 11-digit phone number starting with 0.',
            'phone.regex'    => 'Phone number must be 11 digits and start with 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Guest checkout may have already created a Customer row for this
        // phone/email — link it instead of creating a duplicate.
        $customer = Customer::where('phone', $phone)
            ->orWhere('email', $request->email)
            ->first();

        if ($customer) {
            $customer->user_id = $user->id;
            if (! $customer->email) {
                $customer->email = $request->email;
            }
            if (! $customer->first_name) {
                $customer->first_name = $request->name;
            }
            $customer->save();
        } else {
            $customer = Customer::create([
                'user_id'    => $user->id,
                'first_name' => $request->name,
                'email'      => $request->email,
                'phone'      => $phone,
                'role'       => 'user',
            ]);
        }

        return response()->json([
            'message' => 'Account created successfully.',
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $login = trim($request->login);
        $email = $login;

        if (! filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Treat the input as a phone number — normalize it the same way
            // registration does (strip +88/88, require 11 digits from 0)
            // before resolving to the User linked to the matching Customer.
            $phone = Phone::normalizeBd($login);

            if (! $phone) {
                return response()->json([
                    'message' => 'Enter a valid email or an 11-digit phone number starting with 0.',
                ], 422);
            }

            $customer = Customer::where('phone', $phone)->first();

            if (! $customer || ! $customer->user_id) {
                return response()->json([
                    'message' => 'No account found for this phone number.',
                ], 422);
            }

            $linkedUser = User::find($customer->user_id);

            if (! $linkedUser) {
                return response()->json([
                    'message' => 'No account found for this phone number.',
                ], 422);
            }

            $email = $linkedUser->email;
        }

        if (! Auth::attempt(['email' => $email, 'password' => $request->password], $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 422);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        return response()->json([
            'message' => 'Signed in successfully.',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Signed out successfully.',
        ]);
    }
}
