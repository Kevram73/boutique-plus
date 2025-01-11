<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    public function  modeleFournisseur(){
         return $this->hasMany('App\modeleFournisseur');
    }

    public function recettes(){
        return $this->hasMany('App\Reccete', 'fournisseur_id');
    }

    protected $guarded = ['solde'];

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
