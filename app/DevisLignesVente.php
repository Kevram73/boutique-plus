<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevisLignesVente extends Model
{
    public function  modelefournisseur(){
        return $this->hasMany('App\modeleFournisseur');
    }

    protected $guarded = ['reduction', 'prixtotal'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->formatAttributes)) {
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

