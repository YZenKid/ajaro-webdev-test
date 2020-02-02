<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxPurchaseDetail extends Model
{
    protected $table = 'trx_purchase_details';

    protected $fillable = [
        'trx_purchase_id',
        'product_id',
        'supplier_id',
        'quantity',
        'sub_total_price'
    ];
}
