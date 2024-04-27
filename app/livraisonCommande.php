<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class livraisonCommande extends Model
{
    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'livraison_id');
    }

    public function commandeModele()
    {
        return $this->belongsTo(commandeModele::class, 'commande_modele_id');
    }
    
    public function modele_produit()
    {
        return Modele::find($this->modele);
    }
}
