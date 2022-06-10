<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $addresses = auth()->user()->addresses;

        if ($addresses->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'info',
                'message' => 'No data found!!'
            ], 204);
        }

        return response()->json([
            'data' => $addresses,
            'status' => 'success',
            'message' => 'User addresses list!!'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'street_one' => 'required|string',
            'area' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required',
            'state' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $address = new Address;
        $address->street_one = $request->street_one;
        $address->street_two = $request->street_two;
        $address->area = $request->area;
        $address->city = $request->city;
        $address->zipcode = $request->zipcode;
        $address->state = $request->state;
        auth()->user()->addresses()->save($address);

        return response()->json([
            'data' => $address,
            'status' => 'success',
            'message' => 'Address added successfully!!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($address)
    {
        $address = Address::find($address);

        if (! $address) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid ID entered'
            ], 422);
        }

        return response()->json([
            'data' => $address,
            'status' => 'success',
            'message' => 'Address details'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($address)
    {
        $address = Address::find($address);

        if (! $address) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid ID entered'
            ], 422);
        }

        return response()->json([
            'data' => $address,
            'status' => 'success',
            'message' => 'Address details'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $address)
    {
        $validator = Validator::make($request->all(), [
            'street_one' => 'required|string',
            'area' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required',
            'state' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $address = Address::find($address);

        if (! $address) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid ID entered'
            ], 422);
        }

        $address->street_one = $request->street_one;
        $address->street_two = $request->street_two;
        $address->area = $request->area;
        $address->city = $request->city;
        $address->zipcode = $request->zipcode;
        $address->state = $request->state;
        $address->save();

        return response()->json([
            'data' => $address,
            'status' => 'success',
            'message' => 'Address has been updated successfully!!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($address)
    {
        $address = Address::find($address);

        if (! $address) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid ID entered'
            ], 422);
        }

        $old = $address;
        $address->delete();

        return response()->json([
            'data' => $old,
            'status' => 'success',
            'message' => 'Address has been deleted successfully!!'
        ], 200);
    }
}
