<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeRecette extends Model
{
    protected $fillable  = [
        'id'
    ];

    public function recettes(){
        return $this->hasMany('App\Reccete', 'type_id');
    }
}
