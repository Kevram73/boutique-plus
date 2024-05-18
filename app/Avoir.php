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
}
