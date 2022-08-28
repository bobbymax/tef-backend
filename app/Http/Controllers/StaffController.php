<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->get();

        if ($users->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No Data Found!!'
            ], 200);
        }

        return response()->json([
            'data' => $users,
            'status' => 'success',
            'message' => 'Staff List!!'
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'role_id' => 'required|integer',
            'mobile' => 'required|string|max:255|unique:users',
            'type' => 'required|string|max:255|in:staff,dispatch,adhoc,support'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s)!:'
            ], 500);
        }

        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'mobile' => $request->mobile,
            'designation' => $request->designation,
            'password' => Hash::make('Password1'),
            'type' => $request->type,
        ]);

        return response()->json([
            'data' => $staff,
            'status' => 'success',
            'message' => 'Staff has been created successfully!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::find($user);

        if (! $user) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid user id'
            ], 422);
        }

        return response()->json([
            'data' => $user,
            'status' => 'success',
            'message' => 'User Details'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        $user = User::find($user);

        if (! $user) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid user id'
            ], 422);
        }

        return response()->json([
            'data' => $user,
            'status' => 'success',
            'message' => 'User Details'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,'.$user,
            'role_id' => 'required|integer',
            'mobile' => 'required|string|max:255|unique:users,mobile,'.$user,
            'type' => 'required|string|max:255|in:staff,dispatch,adhoc,support'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s)!:'
            ], 500);
        }

        $user = User::find($user);

        if (! $user) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid user id'
            ], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'mobile' => $request->mobile,
            'designation' => $request->designation,
            'type' => $request->type,
        ]);

        return response()->json([
            'data' => $user,
            'status' => 'success',
            'message' => 'Staff has been updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $user = User::find($user);

        if (! $user) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid user id'
            ], 422);
        }

        $old = $user;
        $user->delete();

        return response()->json([
            'data' => $old,
            'status' => 'success',
            'message' => 'Staff has been deleted successfully!'
        ], 200);
    }
}
