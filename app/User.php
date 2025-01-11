<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\modelHasRole;
use App\Role;
use App\Boutique;


class User extends Authenticatable
{
    use Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function caisse(){
        return $this->hasOne('App\Caisse');
    }
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }
    public function role()
    {
        return $this->hasOne('App\Role');
    }

    public function recettes(){
        return $this->hasMany('App\Reccete', 'user_id');
    }
}
