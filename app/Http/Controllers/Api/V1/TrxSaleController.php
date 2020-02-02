<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Product;
use App\TrxSale;
use App\TrxSaleDetail;
use Illuminate\Http\Request;

use DB;

class TrxSaleController extends Controller
{
    public function index()
    {
        return response()->json(TrxSale::with(['trxSaleDetail'])->orderBy('id', 'desc')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale.*.product_id'     => 'required',
            'sale.*.quantity'       => 'required'
        ]);

        $post = request()->all();
        $user = auth()->user();

        $totalPrice = 0;
        foreach ($post['sale'] as $key => $value) {
            $getProduct = Product::where('id', $value['product_id'])->first();
            $post['sale'][$key]['sub_total_price'] = $getProduct->price_sale * $value['quantity'];
            $totalPrice += $getProduct->price_sale * $value['quantity'];
        }

        $getLast = TrxSale::orderBy('id', 'desc')->first();
        if (!$getLast) {
            $code = 1;
        } else {
            $code = $getLast->id;
        }

        DB::beginTransaction();

        try {
            $saveSale = TrxSale::create([
                'trx_code'      => 'TRX-PR-' . str_pad($code + 1, 4, '0', STR_PAD_LEFT) . '-' . time(),
                'total_price'   => $totalPrice
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Error ' . $e->getMessage()
            ]);
        }

        foreach ($post['sale'] as $key => $value) {
            $post['sale'][$key]['trx_sale_id'] = $saveSale->id;
        }

        try {
            $saveDetailSale = TrxSaleDetail::insert($post['sale']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Error ' . $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status'        => 'success',
            'messages'      => 'Success create new transaction sale',
            'result'        => [
                'User'              => $user->name,
                'Transaction Code'  => $saveSale->trx_code,
                'Total Price'       => $saveSale->total_price
            ],
            'url_detail'    => url()->current() . '/' . $saveSale->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(TrxSale::with(['trxSaleDetail'])->findOrFail($id));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TrxSale::destroy($id);

        return response()->json([
            'messages' => [
                'Success delete transaction sale data'
            ]
        ]);
    }
}
