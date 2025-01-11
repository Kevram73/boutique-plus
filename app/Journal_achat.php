<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal_achat extends Model
{
    public function boutique(){
    return $this->belongsTo('App\Boutique');
}
    public function commande(){
    return $this->belongsTo('App\Commande');
}

}
