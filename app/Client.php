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

    protected $guarded = ['solde', 'avoir'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->guarded)) {
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
