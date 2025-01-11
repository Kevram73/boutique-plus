<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;



class Client extends Authenticatable
{
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    public function reglements() {
        return $this->hasMany('App\Reglement');
    }

    public function getSoldeAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Accessor pour "avoir"
    public function getAvoirAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        // Vérifie si la valeur est numérique avant de la formater
        if (is_numeric($value)) {
            return number_format($value, 2, ',', ' ');
        }

        // Retourne la valeur brute si ce n'est pas un nombre
        return $value;
    }


}
