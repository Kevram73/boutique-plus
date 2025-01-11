<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevisVente extends Model
{
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    protected $guarded = ['montant_reduction', 'totaux'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->guarded)) {
            $value = parent::__get($key);
            return $this->formatAmount($value);
        }

        return parent::__get($key);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        return number_format($value, 2, ',', ' ');
    }
}
