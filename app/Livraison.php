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

    public function livraison_lines(){
        return livraisonCommande::where('livraison_id', $this->id)->get();
    }

    public function qte_liv(){
        $livraison_lines = $this->livraison_lines();
        $qte_liv = 0;
        foreach($livraison_lines as $line){
            $qte_liv += $line->quantite_livre;
        }

        return $qte_liv;
    }

    public function qte_sell(){
        $livraison_lines = $this->livraison_lines();
        $qte_sell = 0;
        foreach($livraison_lines as $line){
            $qte_sell += $line->quantite_vendue;
        }

        return $qte_sell;
    }

    public function statut(){
        if($this->qte_sell() == 0){
            return 'Pas encore vendue';
        } else if($this->qte_liv() > $this->qte_sell()){
            return 'En partie vendue';
        } else if($this->qte_liv() == $this->qte_sell()){
            return 'Total vendue';
        }

    }
}
