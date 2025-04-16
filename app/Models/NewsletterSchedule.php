<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSchedule extends Model
{
    protected $fillable = [
        'frequency', 'day_of_week', 'day_of_month', 'send_time', 'state_id'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

