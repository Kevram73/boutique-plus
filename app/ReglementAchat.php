<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReglementAchat extends Model
{
    protected $fillable  = [
        'id'
    ];

    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    public function getMontantDonneAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "montant_restant"
    public function getMontantRestantAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "total"
    public function getTotalAttribute($value)
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
