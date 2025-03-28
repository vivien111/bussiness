<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_iso2',
        'code_iso3',
    ];

    /**
     * Relations avec les autres modèles
     */
    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function newsletters()
    {
        return $this->hasMany(Newsletter::class);
    }
}

