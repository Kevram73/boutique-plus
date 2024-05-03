<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avoir extends Model
{
    public function client()
    {
        return Client::find($this->client_id);
    }
}
