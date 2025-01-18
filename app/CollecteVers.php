<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollecteVers extends Model
{
    protected $table = 'collecteVers';
    // public function getMontantCollecteAttribute($value)
    // {
    //     return $this->formatAmount($value);
    // }

    // // Accessor pour "montantVerse"
    // public function getMontantVerseAttribute($value)
    // {
    //     return $this->formatAmount($value);
    // }

    // // Accessor pour "veille"
    // public function getVeilleAttribute($value)
    // {
    //     return $this->formatAmount($value);
    // }

    // // Accessor pour "reste"
    // public function getResteAttribute($value)
    // {
    //     return $this->formatAmount($value);
    // }

    // // Méthode pour formater les montants
    // protected function formatAmount($value)
    // {
    //     // Vérifie si la valeur est numérique avant de la formater
    //     if (is_numeric($value)) {
    //         return number_format($value, 2, ',', ' ');
    //     }

    //     // Retourne la valeur brute si ce n'est pas un nombre
    //     return $value;
    // }
}
