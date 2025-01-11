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

    public function livraison(){
        return livraison::where('numero', $this->livraison)->get()->first();
    }

    protected $fillable = ['prix', 'prixtotal'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->fillable)) {
            $value = parent::__get($key);
            return $this->formatAmount($value);
        }

        return parent::__get($key);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        return number_format($value, 2, ',', ' ');
    }
}

