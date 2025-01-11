<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{

    public function  provisions(){
        return $this->hasMany('App\Provision');
    }

    public  function produitProvision(){
        return $this->hasMany('App\produitProvision');
    }
    public function categorie(){
        return $this->belongsTo('App\Categorie');
    }
    public  function modele(){
        return $this->hasMany('App\Modele');
    }

    
}
