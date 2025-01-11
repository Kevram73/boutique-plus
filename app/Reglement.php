<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $guarded  = [
        'id'
    ];

    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    public function vente() {
        return $this->belongsTo('App\vente');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getSoldeTotalAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "autre_champ"
    public function getAutreChampAttribute($value)
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
