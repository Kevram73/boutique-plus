<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sold extends Model
{
    protected $fillable  = [
        'id'
    ];

    protected $fillable = ['montant'];

    // Accessor générique
    public function __get($key)
    {
        if (in_array($key, $this->fillable)) {
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
