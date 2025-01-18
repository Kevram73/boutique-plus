<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modeleFournisseur extends Model
{
    public  function fournisseur(){

    return $this->belongsTo('App\Fournisseur');
}
    public  function modele(){

        return $this->belongsTo('App\Modele');
    }
    public  function produit(){

        return $this->belongsTo('App\Produit');
    }
    public  function commande(){

        return $this->belongsTo('App\Commande');
    }
    public function  commandeModele(){
        return $this->hasMany('App\commandeModele');
    }

    // protected $fillable = ['prix'];

    // // Accessor générique
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
