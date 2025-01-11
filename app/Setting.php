<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable  = [
        'id'
    ];

    public function boutiques()
    {
        return $this->belongsToMany('App\Boutique', 'boutique_settings', 'boutique_id', 'setting_id')
                    ->withPivot(["is_active", "key", "value"])
                    ->withTimestamps();
    }
}
