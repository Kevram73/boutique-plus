<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    public  function  user(){
        return $this->belongsTo('App\User');
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    protected $guarded = [
        'solde',
        'soldeMagasin',
        'montantcollecte',
        'remise',
        'ventenette',
        'totalVente',
        'totalDepense',
        'recouvrementInte',
        'venteCredit',
        'recetteTotal',
        'ventenonlivre',
        'avoir',
    ];

    // Accessor générique pour les attributs à formater
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
