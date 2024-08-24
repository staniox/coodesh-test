<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }

    public function show($code)
    {
        $product = Product::where('code', $code)->first();
        return response()->json($product);
    }

    public function update(Request $request, $code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update(['status' => 'trash']);
        return response()->json(['message' => 'Product moved to trash.']);
    }
}
