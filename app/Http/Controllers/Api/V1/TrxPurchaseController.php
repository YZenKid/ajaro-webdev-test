<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Product;
use App\TrxPurchase;
use App\TrxPurchaseDetail;
use Illuminate\Http\Request;

use DB;

class TrxPurchaseController extends Controller
{
    public function index()
    {
        return response()->json(TrxPurchase::with(['trxPurchaseDetail'])->orderBy('id', 'desc')->paginate(10));
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
            'purchase.*.product_id'     => 'required',
            'purchase.*.supplier_id'    => 'required',
            'purchase.*.quantity'       => 'required'
        ]);

        $post = request()->all();
        $user = auth()->user();

        $totalPrice = 0;
        foreach ($post['purchase'] as $key => $value) {
            $getProduct = Product::where('id', $value['product_id'])->first();
            $post['purchase'][$key]['sub_total_price'] = $getProduct->price_purchase * $value['quantity'];
            $totalPrice += $getProduct->price_purchase * $value['quantity'];
        }

        $getLast = TrxPurchase::orderBy('id', 'desc')->first();
        if (!$getLast) {
            $code = 1;
        } else {
            $code = $getLast->id;
        }

        DB::beginTransaction();

        try {
            $savePurchase = TrxPurchase::create([
                'user_id'       => $user->id,
                'trx_code'      => 'TRX-PR-' . str_pad($getLast->id + 1, 4, '0', STR_PAD_LEFT) . '-' . time(),
                'total_price'   => $totalPrice
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'fail',
                'messages'  => 'Error ' . $e->getMessage()
            ]);
        }

        foreach ($post['purchase'] as $key => $value) {
            $post['purchase'][$key]['trx_purchase_id'] = $savePurchase->id;
        }

        try {
            $saveDetailPurchase = TrxPurchaseDetail::insert($post['purchase']);
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
            'messages'      => 'Success create new transaction purchase',
            'result'        => [
                'User'              => $user->name,
                'Transaction Code'  => $savePurchase->trx_code,
                'Total Price'       => $savePurchase->total_price
            ],
            'url_detail'    => url()->current() . '/' . $savePurchase->id
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
        return response()->json(TrxPurchase::with(['trxPurchaseDetail'])->findOrFail($id));
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
        TrxPurchase::destroy($id);

        return response()->json([
            'messages' => [
                'Success delete transaction purchase data'
            ]
        ]);
    }
}
