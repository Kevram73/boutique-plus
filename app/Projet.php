<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable  = [
        'id'
    ];

    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

   
    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        return number_format($value, 2, ',', ' ');
    }
}
