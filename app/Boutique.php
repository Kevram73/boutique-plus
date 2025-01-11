<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    protected $fillable = [
        'nom','telephone','adresse'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function commande(){
        return $this->belongsTo('App\Commande');
    }

    public function recettes(){
        return $this->hasMany('App\Reccete', 'boutique_id');
    }

    public function settings()
    {
        return $this->belongsToMany('App\Setting', 'boutique_settings', 'boutique_id', 'setting_id')
                    ->withPivot(["is_active", "key", "value"])
                    ->withTimestamps();
    }
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function caisses()
    {
        return $this->hasMany(Caisse::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function reglements()
    {
        return $this->hasManyThrough(Reglement::class, Client::class);
    }

    public function getValueAttribute($value)
    {
        return $this->formatAmount($value);
    }

    // Méthode pour formater les montants
    protected function formatAmount($value)
    {
        // Vérifie si la valeur est numérique avant de la formater
        if (is_numeric($value)) {
            return number_format($value, 2, ',', ' ');
        }

        // Retourne la valeur brute si ce n'est pas un nombre
        return $value;
    }
}
