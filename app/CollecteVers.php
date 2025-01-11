<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollecteVers extends Model
{
    protected $table = 'collecteVers';
    protected $fillable = ['montantCollecte', 'montantVerse', 'veille', 'reste'];

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
