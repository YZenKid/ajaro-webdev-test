<?php

namespace App\Http\Controllers;

use App\Product;
use App\TrxSale;
use App\TrxSaleDetail;
use Illuminate\Http\Request;

use DB;

class TrxSaleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['sale']       = TrxSale::get()->toArray();
        $data['product']    = Product::get()->toArray();

        return view('sale', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->except('_token');
        $user = auth()->user();

        // dd($post);

        for ($i = 0; $i < count($post['product']); $i++) {
            $post['sale'][$i]['product_id']     = $post['product'][$i];
            $post['sale'][$i]['quantity']       = $post['quantity'][$i];
        }

        unset($post['product']);
        unset($post['quantity']);

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
            $savePurchase = TrxSale::create([
                'trx_code'      => 'TRX-PR-' . str_pad($getLast->id + 1, 4, '0', STR_PAD_LEFT) . '-' . time(),
                'total_price'   => $totalPrice
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        foreach ($post['sale'] as $key => $value) {
            $post['sale'][$key]['trx_sale_id'] = $savePurchase->id;
        }

        try {
            $saveDetailPurchase = TrxSaleDetail::insert($post['sale']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        DB::commit();

        return redirect('sale')->with('success', ['Success create new transaction sale']);
    }
}
