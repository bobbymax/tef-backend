<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
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
        $tags = Tag::latest()->get();

        if ($tags->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No data found!!',
            ], 200);
        }

        return response()->json([
            'data' => $tags,
            'status' => 'success',
            'message' => 'Tags List',
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
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $tag = Tag::create([
            'name' => $request->name,
            'label' => Str::slug($request->name),
        ]);

        return response()->json([
            'data' => $tag,
            'status' => 'success',
            'message' => 'Tag Created Successfully!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($tag)
    {
        $tag = Tag::find($tag);

        if (! $tag) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token entered!'
            ], 200);
        }

        return response()->json([
            'data' => $tag,
            'status' => 'success',
            'message' => 'Tag Details'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($tag)
    {
        $tag = Tag::find($tag);

        if (! $tag) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token entered!'
            ], 200);
        }

        return response()->json([
            'data' => $tag,
            'status' => 'success',
            'message' => 'Tag Details'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $tag)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $tag = Tag::find($tag);

        if (! $tag) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token entered!'
            ], 200);
        }

        $tag->update([
            'name' => $request->name,
            'label' => Str::slug($request->name),
        ]);

        return response()->json([
            'data' => $tag,
            'status' => 'success',
            'message' => 'Tag Updated Successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag)
    {
        $tag = Tag::find($tag);

        if (! $tag) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid token entered!'
            ], 200);
        }

        $old = $tag;
        $tag->delete();

        return response()->json([
            'data' => $old,
            'status' => 'success',
            'message' => 'Tag Deleted Successfully!'
        ], 200);
    }
}
