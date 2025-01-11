<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    protected $fillable  = [
        'id'
    ];

    public  function lignes(){
        return $this->hasMany('App\RetourLigne', 'retour_id');
    }
}
