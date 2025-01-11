<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReglementAchat extends Model
{
    protected $guarded  = [
        'id'
    ];

    public function boutique(){
        return $this->belongsTo('App\Boutique');
    }

    protected $guarded = ['montant_donne', 'montant_restant', 'total'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->guarded)) {
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
