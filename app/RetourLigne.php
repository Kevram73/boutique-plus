<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetourLigne extends Model
{
    protected $formatAttributes  = [
        'id'
    ];

    public function retour(){
        return $this->belongsTo('App\Retour', 'retour_id');
    }
}
