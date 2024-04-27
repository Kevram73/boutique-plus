<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Decharge extends Model
{
    protected $fillable = [
        'nom',
        'prenoms',
        'cni',
        'motif',
        'montant',
        'fournisseur_id',
        'filename'
    ];
    public function fournisseur(){
        return $this->belongsTo('App\Fournisseur');
    }
    
}