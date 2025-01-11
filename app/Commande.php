<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    public  function livraison(){
        return $this->hasMany('App\Livraison');
    }
    public function  commandeModele(){
        return $this->hasMany('App\commandeModele');
    }
    public function  modeleFournisseur(){
        return $this->hasMany('App\modeleFournisseur');
    }
    public function journal_achat(){
        return $this->belongsTo('App\Journal_achat');
    }
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

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
