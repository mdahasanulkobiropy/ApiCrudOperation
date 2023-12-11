<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No Product found'], 200);
        }       
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try{
            $product =Product::create([
                'name' => $request->name,
                'short_des' => $request->short_des,
                'long_des' => $request->long_des,
            ]);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . uniqid() . "." . $image->extension();
                $location = public_path('image/product');
                $image->move($location, $filename);
                $product->image = $filename;
            }
            $product->save();
            return response()->json([
                'message' => 'Purchase created successfully',
                'data' => new ProductResource($product),
            ],200);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'An error occured :' . $e->getMessage()],500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try{
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }   
            $product->update([
                'name' => $request->name,
                'short_des' => $request->short_des,
                'long_des' => $request->long_des,
            ]);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . uniqid() . "." . $image->extension();
                $location = public_path('image/product');
                $image->move($location, $filename);
                $product->image = $filename;
            }
            $product->update();
            return response()->json([
                'message' => 'Purchase updated successfully',
                'data' => new ProductResource($product),
            ],200);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'An error occured :' . $e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }    
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
