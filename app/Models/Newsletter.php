<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'state_id',
        'scheduled_at',
        'status',
        'locale',
    ];

    /**
     * Relations avec les autres modèles
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'newsletter_announcement');
    }
    public function scopeByLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }
}

