<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
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
        //
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
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $image)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'size' => 'required|integer',
            'type' => 'required|string|max:255',
            'path' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $image = Image::find($image);

        if (! $image) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid Token Input'
            ], 422);
        }

        $image->update([
            'name' => $request->name,
            'size' => $request->size,
            'type' => $request->type,
            'path' => $request->path
        ]);

        return response()->json([
            'data' => $image,
            'status' => 'success',
            'message' => 'Product Image Updated Successfully!!'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($image)
    {
        $image = Image::find($image);

        if (! $image) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid Token Input'
            ], 422);
        }

        $old = $image;
        $image->delete();

        return response()->json([
            'data' => $old,
            'status' => 'success',
            'message' => 'Product Image Updated Successfully!!'
        ],200);
    }
}
