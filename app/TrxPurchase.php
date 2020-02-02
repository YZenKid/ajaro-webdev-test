<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxPurchase extends Model
{
    protected $table = 'trx_purchases';

    protected $fillable = [
        'user_id',
        'trx_code',
        'total_price'
    ];

    public function trxPurchaseDetail()
    {
        return $this->hasMany('App\TrxPurchaseDetail');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
