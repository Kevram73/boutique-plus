<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LivraisonV extends Model
{
    public function prevente(){
        return $this->belongsTo('App\Prevente');
    }
    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

   

}
