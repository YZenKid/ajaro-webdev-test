<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxSaleDetail extends Model
{
    protected $table = 'trx_sale_details';

    protected $fillable = [
        'trx_sale_id',
        'product_id',
        'quantity',
        'sub_total_price'
    ];
}
