<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $user;
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
        $orders = Order::latest()->get();

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
            'user_id' => 'required',
            'address_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'mobile' => 'required|string|unique:users',
            'trnxId' => 'required|string|unique:orders',
            'orderId' => 'required|string|unique:carts',
            'total_amount' => 'required|integer',
            'amount_received' => 'required',
            'payment_method' => 'required|string|max:255|in:electronic,cash',
            'paid' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        if ($request->user_id > 0) {
            $this->user = User::find($request->user_id);
        } else {
            $this->user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make('Password1')
            ]);
        }

        if (! $this->user) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'This User record was not created!!'
            ], 500);
        }

        // Add Address if no address exists for user
        if ($request->address_id == 0) {
            $address = new Address;
            $address->house_no = $request->house_no;
            $address->street_one = $request->street_one;
            $address->street_two = $request->street_two;
            $address->area = $request->area;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->isCurrent = true;

            $this->user->addresses()->save($address);
        } else {
            $address = Address::find($request->address_id);

            if ($address)
                $address->isCurrent = true;
                $address->save();
        }

        $cart = Cart::create([
            'orderId' => $request->orderId,
            'total_amount' => $request->total_amount
        ]);

        if (! $cart) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Something went terribly wrong!!'
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

        $order = Order::create([
            'cart_id' => $cart->id,
            'user_id' => $this->user->id,
            'trnxId' => $request->trnxId,
            'amount_received' => $request->amount_received,
            'additional_info' => $request->additional_info,
            'payment_method' => $request->payment_method,
            'paid' => $request->paid
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($order)
    {
        $order = Order::find($order);

        if (! $order) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        return response()->json([
            'data' => $order,
            'status' => 'success',
            'message' => 'Order Details'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($order)
    {
        $order = Order::find($order);

        if (! $order) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token entered'
            ], 422);
        }

        return response()->json([
            'data' => $order,
            'status' => 'success',
            'message' => 'Order Details'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $order)
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

        $order = Order::find($order);

        if (! $order) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token enteres'
            ], 422);
        }

        $order->update([
            'paid' => $request->paid,
            'closed' => true
        ]);

        return response()->json([
            'data' => $order,
            'status' => 'success',
            'message' => 'Order has been updated successfully!!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
