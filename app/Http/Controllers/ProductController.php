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
            'name.unique' => 'منتج يحمل نفس الأسم ونفس الوزن موجود مسبقاً.',
            'weight.required' => 'حقل الوزن مطلوب لتحديد هوية المنتج الفريدة.',
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
                'message' => 'تم انشاء المنتج بنجاح',
                'product' => $product
            ], 201);
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
                })->ignore($product->id), // هذا الجزء هو الأهم لتجاهل المنتج الحالي
            ],
            'description' => ['nullable', 'string'],
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

    public function get_product(Product $product){
        return response()->json([
            'message' => 'هذه هي معلومات المنتح المطلوب',
            'Product' => $product
        ]);
    }
}
