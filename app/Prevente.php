<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prevente extends Model
{
    public function  modelefournisseur(){
        return $this->hasMany('App\modeleFournisseur');
    }

    public function modele(){
        $modele = Modele::find($this->modele_fournisseur_id);
        return $modele;
    }

    public function vente(){
        return vente::find($this->vente_id);
    }
}

