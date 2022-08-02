<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
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
        $items = CartItem::latest()->get();

        if ($items->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No data found!!',
            ], 200);
        }

        return response()->json([
            'data' => $items,
            'status' => 'success',
            'message' => 'Cart Items List',
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($cartItem)
    {
        $cartItem = CartItem::find($cartItem);

        if (! $cartItem) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        return response()->json([
            'data' => $cartItem,
            'status' => 'success',
            'message' => 'Order Details'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($cartItem)
    {
        $cartItem = CartItem::find($cartItem);

        if (! $cartItem) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        return response()->json([
            'data' => $cartItem,
            'status' => 'success',
            'message' => 'Order Details'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $cartItem)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $cartItem = CartItem::find($cartItem);

        if (! $cartItem) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        $cartItem->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        $cartItem->cart->total_amount = $cartItem->cart->items->sum('price');
        $cartItem->cart->save();

        return response()->json([
            'data' => $cartItem,
            'status' => 'success',
            'message' => 'Cart Item Updated Successfully!!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
