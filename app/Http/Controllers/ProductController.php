<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function create_product(Request $request)
    {
        $messages = [
            'name.unique' => 'A product with the same name and weight already exists.',
            'weight.required' => 'The weight field is required to uniquely identify the product.',
        ];
        $fields = $request->validate([
            'name'        => ['required', 'string', 'max:255',
                                Rule::unique('products')->where(function($query) use ($request){
                                    return $query->where('name', $request->name)
                                                 ->where('weight', $request->weight);
                                })],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'cost'        => ['nullable', 'numeric', 'min:0'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'weight'      => ['required', 'numeric', 'min:0'],
            'is_active'   => ['boolean', 'nullable'],
        ], $messages);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'cost' => $request->cost,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
                'message' => 'The product was created successfully.',
                'product' => $product
            ], 200);
    }

    public function edit_product(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name'        => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)
                                 ->where('weight', $request->weight);
                })->ignore($product->id),
            ],
            'description' => ['sometimes','nullable', 'string'],
            'price'       => ['sometimes', 'numeric', 'min:0'],
            'cost'        => ['nullable', 'numeric', 'min:0'],
            'quantity'    => ['sometimes', 'integer', 'min:0'],
            'weight'      => ['sometimes', 'numeric', 'min:0'],
            'is_active'   => ['boolean', 'nullable'],
        ]);

        $product->update($validatedData);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ], 200);
    }

    public function get_product(Product $product, Request $request){
        if($request->user()->role->id == 1){
            return response()->json([
                'message' => 'This is the information for the requested product.',
                'Product' => $product
            ],200);
        } else {
            if($product->is_active){
                return response()->json([
                    'message' => 'This is the information for the requested product.',
                    'Product' => $product
                ],200);
            } else{
                return response()->json([
                    'message' => 'You do not have permission to view the requested product information.'
                ],401);
            }
        }
    }

    public function products_search(Request $request){
        $search = $request->input('query');

        if($request->user()->role->id == 1){
            if ($search) {
                $products = Product::where('name', 'LIKE', "%{$search}%")->get();
            } else {
                $products = Product::all();
            }
        }else {
            if ($search) {
                $products = Product::where('name', 'LIKE', "%{$search}%")->where('is_active', true)->get();
            } else {
                $products = Product::where('is_active', true)->get();
            }
        }
        return response()->json($products, 200);
    }

    public function product_frees_or_unfrees(Product $product){
        if($product->is_active){
            $product->is_active = false;
            $product->save();
            return response()->json([
                'message' => 'This product has been deactivated.',
                'Product' => $product
            ],200);
        } else {
            $product->is_active = true;
            $product->save();
            return response()->json([
                'message' => 'This product has been activated.',
                'Product' => $product
            ],200);
        }
    }
}
