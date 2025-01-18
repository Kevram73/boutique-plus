<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produit_modele extends Model
{
    protected $fillable = ['solde_total', 'autre_champ'];

    // Accessor générique
    // public function __get($key)
    // {
    //     if (in_array($key, $this->fillable)) {
    //         $value = parent::__get($key);
    //         return $this->formatAmount($value);
    //     }

    //     return parent::__get($key);
    // }

    // // Méthode pour formater les montants
    // protected function formatAmount($value)
    // {
    //     return number_format($value, 2, ',', ' ');
    // }
}
