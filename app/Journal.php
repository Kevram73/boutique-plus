<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    
}
