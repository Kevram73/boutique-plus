<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class livraisonCommande extends Model
{
    protected $fillable = [
        'commande_modele_id',
        'livraison_id',
        'modele_id',
        'quantite_livre',
        'quantite_restante'
    ];

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
        return Modele::find($this->modele_id);
    }
}
