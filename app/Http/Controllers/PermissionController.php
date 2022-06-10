<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $permissions = Permission::latest()->get();

        if ($permissions->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'info',
                'message' => 'No data found!'
            ], 204);
        }

        return response()->json([
            'data' => $permissions,
            'status' => 'success',
            'message' => 'Permissions List'
        ], 200);
    }
}
