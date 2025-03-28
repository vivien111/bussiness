<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model  // Note: Singular "Role" is more conventional for model names
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    // Typically a role has many users
    public function users()
    {
        return $this->belongsToMany(User::class); // Many-to-many relationship
    }
    public function plans()
    {
        return $this->belongsToMany(Plans::class); // Many-to-many relationship
    }
}