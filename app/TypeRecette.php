<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeRecette extends Model
{
    protected $formatAttributes  = [
        'id'
    ];

    public function recettes(){
        return $this->hasMany('App\Reccete', 'type_id');
    }
}
