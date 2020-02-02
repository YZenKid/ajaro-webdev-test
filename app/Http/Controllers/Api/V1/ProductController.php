<?php

namespace App\Http\Controllers\Api\V1;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('category')
                ->orderBy('id', 'desc')
                ->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];

        $request->validate([
            'name' => 'required|max:200',
            'desc' => 'nullable',
            'category_id' => 'required|integer',
            'price_purchase' => 'required|numeric',
            'price_sale' => 'required|numeric'
        ]);

        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'price_purchase' => $request->price_purchase,
            'price_sale' => $request->price_sale
        ];

        if ($request->has('stock')) {
            $request->validate([
                'stock' => 'nullable|numeric|min:1'
            ]);
            $data['stock'] = $request->stock;
        }

        return Product::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('name')) {
            $request->validate([
                'name' => 'required|max:200'
            ]);
            $product->name = $request->name;
        }

        if ($request->has('desc')) {
            $request->validate([
                'desc' => 'nullable'
            ]);
            $product->desc = $request->desc;
        }

        if ($request->has('category_id')) {
            $request->validate([
                'category_id' => 'required|integer'
            ]);
            $product->category_id = $request->category_id;
        }

        if ($request->has('price_purchase')) {
            $request->validate([
                'price_purchase' => 'required|numeric'
            ]);
            $product->price_purchase = $request->price_purchase;
        }

        if ($request->has('price_sale')) {
            $request->validate([
                'price_sale' => 'required|numeric'
            ]);
            $product->price_sale = $request->price_sale;
        }

        if ($request->has('stock')) {
            $request->validate([
                'stock' => 'nullable|numeric|min:1'
            ]);
            $product->stock = $request->stock;
        }

        $product->save();

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);

        return response()->json([
            'message' => 'success delete product data'
        ], 200);
    }
}
