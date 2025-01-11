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
        return $this->belongsTo(Modele::class, 'modele_id');
    }

    public function livraisons()
    {
        return $this->hasMany(LivraisonCommande::class, 'commande_modele_id');
    }

    protected $fillable = ['prix', 'total'];

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
