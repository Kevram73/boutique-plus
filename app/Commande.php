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

    protected $formatAttributes = ['totaux'];

    // Accessor générique
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
