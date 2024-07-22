<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['country_name', 'country_code'];
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
