<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diet extends Model
{
    protected $fillable = [
        'patient_id',
        'patient_measurement_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dietDays(): HasMany
    {
        return $this->hasMany(DietDay::class)->orderBy('date');
    }

    // In Diet model - ADD THIS
    public function measurement(): BelongsTo
    {
        return $this->belongsTo(PatientMeasurement::class, 'patient_measurement_id');
    }
}