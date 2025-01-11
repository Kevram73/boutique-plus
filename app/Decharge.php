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

    protected $formatAttributes = ['montant'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->formatAttributes)) {
            $value = parent::__get($key);
            return $this->formatAmount($value);
        }

        return parent::__get($key);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        return number_format($value, 2, ',', ' ');
    }

}
