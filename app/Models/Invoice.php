<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'paid_at',
    ];

    protected $dates = ['paid_at'];

    /**
     * Relations avec les autres modèles
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}

