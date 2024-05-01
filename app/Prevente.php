<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prevente extends Model
{
    public function  modelefournisseur(){
        return $this->hasMany('App\modeleFournisseur');
    }

    public function modele(){
        $modeleF = modeleFournisseur::find($this->modele_fournisseur_id);
        $modele = Modele::find($modeleF->modele_id);
        return $modele;
    }

    public function vente(){
        return vente::find($this->vente_id);
    }
}

