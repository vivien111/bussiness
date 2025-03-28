<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'features',
        'monthly_price_id',
        'yearly_price_id',
        'onetime_price_id',
        'active',
        'role_id',
        'default',
        'monthly_price',
        'yearly_price',
        'onetime_price',
        'user_id' // Ajouté pour correspondre à votre demande précédente
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'default' => 'boolean',
        'features' => 'array', // Si vous stockez les features sous forme JSON
    ];

    /**
     * Relation avec le modèle Role
     */
    public function roles()
    {
        return $this->belongsTo(Roles::class);
    }

    /**
     * Relation avec le modèle User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les plans actifs
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour le plan par défaut
     */
    public function scopeDefault($query)
    {
        return $query->where('default', true);
    }

    /**
     * Formate le prix mensuel
     */
    public function getMonthlyPriceAttribute($value)
    {
        return $value ? '$' . number_format($value, 2) : null;
    }

    /**
     * Formate le prix annuel
     */
    public function getYearlyPriceAttribute($value)
    {
        return $value ? '$' . number_format($value, 2) : null;
    }

    /**
     * Formate le prix unique
     */
    public function getOnetimePriceAttribute($value)
    {
        return $value ? '$' . number_format($value, 2) : null;
    }

    /**
     * Vérifie si le plan est gratuit
     */
    public function isFree()
    {
        return empty($this->monthly_price) && empty($this->yearly_price) && empty($this->onetime_price);
    }
}