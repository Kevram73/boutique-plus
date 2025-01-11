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

    public function getMontantReductionAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "totaux"
    public function getTotauxAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        // Vérifie si la valeur est numérique avant de la formater
        if (is_numeric($value)) {
            return number_format($value, 2, ',', ' ');
        }

        // Retourne la valeur brute si ce n'est pas un nombre
        return $value;
    }
}
