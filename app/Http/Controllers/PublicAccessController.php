<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Classification;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class PublicAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:api');
    }
    public function getProducts()
    {
        $products = Product::latest()->get();

        if ($products->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No data found!!',
            ], 200);
        }

        return response()->json([
            'data' => ProductResource::collection($products),
            'status' => 'success',
            'message' => 'Tags List',
        ], 200);
    }

    public function tags()
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

    public function classifications()
    {
        $classifications = Classification::latest()->get();

        if ($classifications->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No data found!!',
            ], 200);
        }

        return response()->json([
            'data' => $classifications,
            'status' => 'success',
            'message' => 'Tags List',
        ], 200);
    }
    public function categories()
    {
        $categories = Category::latest()->get();

        if ($categories->count() < 1) {
            return response()->json([
                'data' => [],
                'status' => 'warning',
                'message' => 'No data found!!',
            ], 200);
        }

        return response()->json([
            'data' => $categories,
            'status' => 'success',
            'message' => 'Tags List',
        ], 200);
    }
}
