<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new customer.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|unique:customers,phone',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم التسجيل بنجاح',
            'token' => $token,
            'user' => $customer
        ], 201);
    }

    /**
     * Login customer and return token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required_without:phone|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)
            ->first();

        if (!$customer) {
            throw ValidationException::withMessages([
                'message' => 'المستخدم غير موجود',
            ]);
        }

        if (!Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'message' => 'كلمة المرور غير صحيحة',
            ]);
        }

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'user' => $customer
        ]);
    }

    /**
     * Logout customer (Revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }

    /**
     * Get authenticated customer details.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
