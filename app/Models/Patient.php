<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
    ];

    protected $casts = [
        'gender' => 'boolean',
        'date_of_birth' => 'date',
    ];

    public function measurements(): HasMany
    {
        return $this->hasMany(PatientMeasurement::class)->latest();
    }

    public function diets(): HasMany
    {
        return $this->hasMany(Diet::class);
    }

    public function latestMeasurement()
    {
        return $this->hasOne(PatientMeasurement::class)->latestOfMany();
    }

    // Helper: calculate age
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }
}