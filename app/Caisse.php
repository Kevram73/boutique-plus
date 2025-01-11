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

    public function getSoldeAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "soldeMagasin"
    public function getSoldeMagasinAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "montantcollecte"
    public function getMontantcollecteAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "remise"
    public function getRemiseAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "ventenette"
    public function getVentenetteAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "totalVente"
    public function getTotalVenteAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "totalDepense"
    public function getTotalDepenseAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "recouvrementInte"
    public function getRecouvrementInteAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "venteCredit"
    public function getVenteCreditAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "recetteTotal"
    public function getRecetteTotalAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "ventenonlivre"
    public function getVentenonlivreAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "avoir"
    public function getAvoirAttribute($value)
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
