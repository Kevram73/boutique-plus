<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avoir extends Model
{
    public function client()
    {
        return Client::find($this->client_id);
    }

    public function user(){
    return User::find($this->user_id);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function getAmountAttribute($value)
    {
        // Formate le montant avec 2 décimales et des séparateurs de milliers
        return number_format($value, 2, ',', ' ');
    }
}
