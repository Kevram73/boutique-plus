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

    public function getWithAvoirAttribute($value)
{
    return number_format($value, 2, ',', ' ');
}

public function getMontantReductionAttribute($value)
{
    return number_format($value, 2, ',', ' ');
}

public function getTotauxAttribute($value)
{
    return number_format($value, 2, ',', ' ');
}
}
