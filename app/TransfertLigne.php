<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransfertLigne extends Model
{
    protected $table = "transfert_lignes";

    protected $fillable = [
        'modele_reception_id'
    ];

    
}
