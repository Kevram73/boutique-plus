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
        return Modele::where('id', $this->modele_id)->first();
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonCommande::class, 'commande_modele_id');
    }
}
