<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Mockery\Generator\StringManipulation\Pass\Pass;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:255',
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|url',
            'price' => 'required|numeric',
            'release_date' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock_status' => 'required|in:available,sold out',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = null;
        }

        Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'image' => $imagePath,
            'video' => $request->video,
            'price' => $request->price,
            'release_date' => $request->release_date,
            'discount' => $request->discount,
            'stock_status' => $request->stock_status,
        ]);

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    public function index()
    {
        $products = Product::with('user')->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('user')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|url',
            'price' => 'sometimes|required|numeric',
            'release_date' => 'sometimes|nullable|date',
            'discount' => 'sometimes|nullable|numeric|min:0|max:100',
            'stock_status' => 'sometimes|required|in:available,sold out',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->update($request->only(['name', 'video', 'price', 'release_date', 'discount', 'stock_status']));
        
        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
