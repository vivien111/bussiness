<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'code',
    ];

    /**
     * Relations avec les autres modèles
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function newsletters()
    {
        return $this->hasMany(Newsletter::class);
    }
}

