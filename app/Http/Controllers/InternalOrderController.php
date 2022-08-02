<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\InternalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternalOrderController extends Controller
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
        $orders = InternalOrder::latest()->get();

        if ($orders->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No data found!!',
            ], 200);
        }

        return response()->json([
            'data' => $orders,
            'status' => 'success',
            'message' => 'Orders List',
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
            'carts' => 'required|array',
            'trnxId' => 'required|string|unique:orders',
            'orderId' => 'required|string|unique:carts',
            'table_no' => 'required|string',
            'total_amount' => 'required|integer',
            'amount_received' => 'required',
            'payment_method' => 'required|string|max:255|in:electronic,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $cart = Cart::create([
            'orderId' => $request->orderId,
            'total_amount' => $request->total_amount
        ]);

        if (! $cart) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'The Cart was not created!!'
            ], 500);
        }


        foreach ($request->carts as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        $order = InternalOrder::create([
            'cart_id' => $cart->id,
            'user_id' => auth()->user()->id,
            'trnxId' => $request->trnxId,
            'table_no' => $request->table_no,
            'recipient' => $request->recipient,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'amount_received' => $request->amount_received,
            'additional_info' => $request->additional_info,
            'payment_method' => $request->payment_method,
            'status' => 'processing'
        ]);

        return response()->json([
            'data' => $order,
            'status' => 'success',
            'message' => 'Order Has Been Placed Successfully!!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InternalOrder  $internalOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($internalOrder)
    {
        $internalOrder = InternalOrder::find($internalOrder);

        if (! $internalOrder) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token entered'
            ], 422);
        }

        return response()->json([
            'data' => $internalOrder,
            'status' => 'success',
            'message' => 'Order Details'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InternalOrder  $internalOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($internalOrder)
    {
        $internalOrder = InternalOrder::find($internalOrder);

        if (! $internalOrder) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        return response()->json([
            'data' => $internalOrder,
            'status' => 'success',
            'message' => 'Order Details'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InternalOrder  $internalOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $internalOrder)
    {
        $validator = Validator::make($request->all(), [
            'paid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $internalOrder = InternalOrder::find($internalOrder);

        if (! $internalOrder) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        $internalOrder->update([
            'paid' => $request->paid,
        ]);

        return response()->json([
            'data' => $internalOrder,
            'status' => 'success',
            'message' => 'Order has been updated successfully!!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InternalOrder  $internalOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(InternalOrder $internalOrder)
    {
        //
    }
}
