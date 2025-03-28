<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Announcement extends Model
{
    use HasFactory;
    public $translatable = ['title', 'content'];

    use HasTranslations;

    protected $fillable = [
        'user_id',
        'country_id',
        'state_id',
        'invoice_id',
        'title',
        'content',
        'image',
        'link',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * Relations avec les autres modèles
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function newsletters()
    {
        return $this->belongsToMany(Newsletter::class, 'newsletter_announcement');
    }
}
