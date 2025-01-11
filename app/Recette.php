<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    protected $guarded  = [
        'id'
    ];

    public function type(){
        return $this->belongsTo('App\TypeReccete', 'type_id');
    }

    public function fournisseur(){
        return $this->belongsTo('App\Fournisseur', 'fournisseur_id');
    }

    public function boutique(){
        return $this->belongsTo('App\Boutique', 'boutique_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    
}
