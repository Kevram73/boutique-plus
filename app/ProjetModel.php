<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjetModel extends Model
{
    protected $fillable  = [
        'id'
    ];

    public function modele(){
        return $this->belongsTo('App\Modele')->with('produit');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
