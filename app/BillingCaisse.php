<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingCaisse extends Model
{
    public function getPrixAttribute($value)
    {
        return number_format($value, 2, ',', ' ');
    }

    // Accessor pour formater "total"
    public function getTotalAttribute($value)
    {
        return number_format($value, 2, ',', ' ');
    }
}
