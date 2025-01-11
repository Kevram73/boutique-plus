<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    protected $formatAttributes = ['montant'];

    // Accessor générique pour formater les montants
    public function getAttribute($key)
    {
        // Vérifie si l'attribut doit être formaté
        if (in_array($key, $this->formatAttributes) && isset($this->attributes[$key])) {
            $value = $this->attributes[$key];
            return $this->formatAmount($value);
        }

        // Utilise le comportement par défaut pour les autres attributs
        return parent::getAttribute($key);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        // Vérifie que la valeur est numérique avant de la formater
        if (is_numeric($value)) {
            return number_format($value, 2, ',', ' ');
        }

        // Retourne la valeur brute si ce n'est pas un nombre
        return $value;
    }

 
}
