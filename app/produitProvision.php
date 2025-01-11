<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class produitProvision extends Model
{

    public function produit(){
        return $this->belongsTo('App\Produit');
    }
    public function fournisseur(){
        return $this->belongsTo('App\Fournisseur');
    }
    public function provision(){
        return $this->belongsTo('App\Provision');
    }

    
}
