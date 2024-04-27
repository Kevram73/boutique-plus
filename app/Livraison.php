<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    public function commande(){
        return $this->belongsTo('App\Commande');
    }
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }
    public function commandes()
    {
        return $this->hasMany(livraisonCommande::class, 'livraison_id');
    }
}
