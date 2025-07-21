<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function create_prod (Request $request){
        $fields = $request->validate([
            'product_id' => 'required|exists:Products,id',
            'quntity' => 'required|integer|min:0',
            'PRD' => 'required|date',
            'EXP' => 'required|date'
        ]);

        $product = Product::where('id', $request->product_id)->first();
        if(!$product->is_active) {
            return response()->json([
                'message' => 'You cannot add a production record for this product, because the product is not activated.'
            ],403);
        }
        $productionR = Production::create([
            'product_id' => $request->product_id,
            'quntity' => $request->quntity,
            'PRD' => $request->PRD,
            'EXP' => $request->EXP
        ]);
        return response()->json([
            'message' => 'The production record was added successfully.',
            'prduction Record' => $productionR
        ],200);
    }

    public function showProductionR(Request $request)
    {
        $search = $request->input('product_id');

        if($search){
            $productionR = Production::with('Product')->where('product_id', $search)->get();
        } else {
            $productionR = Production::with('Product')->get();
        }

        return response()->json([
            'message' => 'Production Records retrieved successfully.',
            'prduction Record' => $productionR,
        ], 200);
    }

    public function edit_production(Request $request, Production $productionR)
    {
        $fields = $request->validate([
            'product_id' => 'sometimes|exists:Products,id',
            'quntity' => 'sometimes|integer|min:0',
            'PRD' => 'sometimes|date',
            'EXP' => 'sometimes|date'
        ]);
        $product = Product::where('id', $request->product_id)->first();
        if(!$product->is_active) {
            return response()->json([
                'message' => 'You cannot add a production record for this product, because the product is not activated.'
            ],403);
        }

        $productionR->update($fields);

        return response()->json([
            'message' => 'Production Record updated successfully!',
            'prduction Record' => $productionR
        ], 200);
    }

    public function delete_production(Request $request, Production $productionR)
    {
        $productionR->delete();

        return response()->json([
            'message' => 'The Production Record has been successfully deleted.'
        ], 200);
    }

    public function dailyReportByProduct($date)
    {
        $dailyReports = Production::with('product')
            ->select('product_id', DB::raw('SUM(quntity) as total_quantity'))
            ->whereDate('PRD', $date)
            ->groupBy('product_id')
            ->get();

        return response()->json([
            'message' => 'The Daily Report for Production',
            'date' => $date,
            'report' => $dailyReports
        ]);
    }

    public function monthlyReportByProduct($year, $month)
    {
        $monthlyReports = Production::with('product')
            ->select('product_id', DB::raw('SUM(quntity) as total_quantity'))
            ->whereYear('PRD', $year)
            ->whereMonth('PRD', $month)
            ->groupBy('product_id')
            ->get();

        return response()->json([
            'message' => 'The Monthly Report for Production',
            'year' => $year,
            'month' => $month,
            'report' => $monthlyReports
        ]);
    }
}
