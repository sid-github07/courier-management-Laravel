<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CurrierProductInfo extends Model {

    protected $table = "currier_product_infos";
    // table fields
    protected $guarded = [];

    public function currier_info() {
        return $this->belongsTo(CurrierInfo::class);
    }

    public function courier_type() {
        return $this->belongsTo(CourierType::class, 'currier_type');
    }

}
