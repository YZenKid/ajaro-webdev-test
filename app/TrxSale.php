<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxSale extends Model
{
    protected $table = 'trx_sales';

    protected $fillable = [
        'trx_code',
        'total_price'
    ];

    public function trxSaleDetail()
    {
        return $this->hasMany('App\TrxSaleDetail');
    }
}
