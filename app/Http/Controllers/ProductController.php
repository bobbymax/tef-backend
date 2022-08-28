<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
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
            'title' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required|string|min:3',
            'brand_id' => 'required|integer',
            'classification_id' => 'required|integer',
            'vip' => 'required|integer',
            'tags' => 'required',
            'categories' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $product = Product::create([
            'brand_id' => $request->input('brand_id'),
            'classification_id' => $request->input('classification_id'),
            'title' => $request->input('title'),
            'label' => Str::slug($request->input('title')),
            'price' => $request->input('price'),
            'vip' => $request->input('vip'),
            'description' => $request->input('description'),
        ]);

        if ($product) {
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $path = $file->store('products', 's3');
                $storage = Storage::disk('s3');
                $storage->setVisibility($path, 'public');

                if ($path) {
                    $image = new Image;
                    $image->name = basename($path);
                    $image->size = $file->getSize();
                    $image->type = $file->getClientMimeType();
                    $image->path = $storage->url($path);
                    $product->image()->save($image);
                }
            }

            if ($request->input('categories')) {
                foreach(json_decode($request->input('categories')) as $cat) {
                    $category = Category::find($cat->value);

                    if ($category && ! in_array($category->id, $product->categories->pluck('id')->toArray())) {
                        $product->categories()->save($category);
                    }
                }
            }

            if ($request->input('tags')) {
                foreach(json_decode($request->input('tags')) as $t) {
                    $tag = Tag::find($t->value);

                    if ($tag && ! in_array($tag->id, $product->tags->pluck('id')->toArray())) {
                        $product->tags()->save($tag);
                    }
                }
            }
        }

        return response()->json([
            'data' => new ProductResource($product),
            'status' => 'success',
            'message' => 'Product Added Successfully!!'
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($product)
    {
        $product = Product::find($product);

        if (! $product) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid Token Input'
            ], 422);
        }

        return response()->json([
            'data' => new ProductResource($product),
            'status' => 'success',
            'message' => 'Product Details!!'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($product)
    {
        $product = Product::find($product);

        if (! $product) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid Token Input'
            ], 422);
        }

        return response()->json([
            'data' => new ProductResource($product),
            'status' => 'success',
            'message' => 'Product Details!!'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $product)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required|string|min:3',
            'brand_id' => 'required|integer',
            'classification_id' => 'required|integer',
            'vip' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'error',
                'message' => 'Please fix the following error(s):'
            ], 500);
        }

        $product = Product::find($product);

        if (! $product) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid Token Input'
            ], 422);
        }

        $product->update([
            'brand_id' => $request->brand_id,
            'classification_id' => $request->classification_id,
            'title' => $request->title,
            'label' => Str::slug($request->title),
            'price' => $request->price,
            'vip' => $request->vip,
            'description' => $request->description,
        ]);

        return response()->json([
            'data' => new ProductResource($product),
            'status' => 'success',
            'message' => 'Product Updated Successfully!!'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($product)
    {
        $product = Product::find($product);

        if (! $product) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Invalid Token Input'
            ], 422);
        }

        $old = $product;
        $product->image()->delete();
        $product->categories()->detach();
        $product->tags()->detach();
        $product->delete();

        return response()->json([
            'data' => $old,
            'status' => 'success',
            'message' => 'Product Deleted Successfully!!'
        ],200);
    }
}
