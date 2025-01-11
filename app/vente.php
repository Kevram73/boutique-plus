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

    protected $formatAttributes = ['with_avoir', 'montant_reduction', 'totaux'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->formatAttributes)) {
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
