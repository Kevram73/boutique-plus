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
        return $this->hasMany('App\Reglements');
    }
}
