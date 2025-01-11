<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class commandeModele extends Model
{
    public  function commande(){

        return $this->belongsTo('App\Commande');
    }
    public  function modeleFournisseur(){
        return $this->belongsTo('App\modeleFournisseur');
    }

    public function modele()
    {
        return $this->belongsTo(Modele::class, 'modele_id');
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonCommande::class, 'commande_modele_id');
    }

    public function getPrixAttribute($value)
{
    return $value !== null ? $this->formatAmount($value) : '0,00';
}

public function getTotalAttribute($value)
{
    return $value !== null ? $this->formatAmount($value) : '0,00';
}

}
