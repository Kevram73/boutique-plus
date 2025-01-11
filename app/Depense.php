<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable  = [
        'id'
    ];

    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    public function getMontantAttribute($value)
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
