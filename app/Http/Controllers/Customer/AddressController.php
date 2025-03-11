<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * List all addresses for the authenticated customer.
     */
    public function index(Request $request): JsonResponse
    {
        $customerId = auth()->id();
        $addresses = Address::with('customer')->where('customer_id', $customerId)
            ->get();

        return response()->json($addresses);
    }

    /**
     * Store a new address.
     */
    public function store(Request $request): JsonResponse
    {
        $customerId = auth()->id();

        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'landmark' => 'required|string|max:255',
            'phone' => 'required|string|max:9|min:9',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->default) {
            Address::with('customer')->where('customer_id', $customerId)->update(['default' => false]);
        }

        $address = Address::create([
            'customer_id' => $customerId,
            'city' => $request->city,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'street' => $request->street,
            'landmark' => $request->landmark,
            'phone' => $request->phone,
            'default' => $request->default ?? false,
        ]);

        return response()->json($address, 201);
    }

    /**
     * Update an existing address.
     */
    public function update(Request $request, Address $address): JsonResponse
    {
        $customerId = auth()->id();

        if ($address->customer_id !== $customerId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'street' => 'sometimes|string|max:255',
            'landmark' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:9|min:9',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->default) {
            Address::where('customer_id', $customerId)->update(['default' => false]);
        }

        $address->update($request->only([
            'address',
            'city',
            'street',
            'landmark',
            'phone',
            'latitude',
            'longitude',
            'default'
        ]));
        return response()->json(['message' => 'Address updated successfully', 'address' => $address], 200);
    }

    /**
     * Delete an address.
     */
    public function destroy(Address $address): JsonResponse
    {
        $customerId = auth()->id();

        if ($address->customer_id !== $customerId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the address
        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
