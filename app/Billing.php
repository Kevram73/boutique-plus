<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    public function getValueAttribute($value)
    {
        // Formate la valeur avec 2 décimales et des séparateurs de milliers
        return number_format($value, 2, ',', ' ');
    }
}
