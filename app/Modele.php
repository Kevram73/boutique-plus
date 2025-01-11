<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    public function produit(){
        return $this->belongsTo('App\Produit');
    }
    public  function modeleFournisseur(){
        return $this->hasMany('App\modeleFournisseur');
    }
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    protected $fillable = ['prix_achat', 'prix_tonne'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->fillable)) {
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
