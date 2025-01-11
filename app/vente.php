<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vente extends Model
{
    public function  prevente(){
        return $this->hasMany('App\Prevente');
    }
    public function caisse(){
        return $this->belongsTo('App\Caisse');
    }
    public function client(){
        return $this->belongsTo('App\Client');
    }
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function payment_status(){
        $reglements = Reglement::where('vente_id', $this->id)->get();
        $sum_reglements = 0;
        foreach($reglements as $reglement)
        {
            $sum_reglements += $reglement->montant_donne;
        }
        if($sum_reglements == $this->totaux){
            return true;
        }
        return false;
    }


    public function reglements()
    {
        return $this->hasMany(Reglement::class);
    }

    protected $guarded = ['with_avoir', 'montant_reduction', 'totaux'];

    public function getAttributeValue($key)
    {
        // Si l'attribut doit être formaté
        if (in_array($key, $this->guarded) && isset($this->attributes[$key])) {
            return $this->formatAmount($this->attributes[$key]);
        }

        // Utilise le comportement par défaut pour tous les autres attributs
        return parent::getAttributeValue($key);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        // Vérifie que la valeur est numérique avant de la formater
        if (is_numeric($value)) {
            return number_format($value, 2, ',', ' ');
        }

        // Retourne la valeur brute si ce n'est pas un nombre
        return $value;
    }
}
