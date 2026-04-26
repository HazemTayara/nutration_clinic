<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PatientMeasurement extends Model
{
    protected $fillable = [
        'patient_id',
        'height_cm',
        'weight_kg',
        'activity_level',
        'bee_custom',
        'carb_percentage',
        'protein_percentage',
        'fat_percentage',

        'belly',
        'lower_abdomen',
        'hips',
        'right_arm',
        'left_arm',
        'right_thigh',
        'left_thigh',
        'right_calf',
        'left_calf',
    ];

    protected $casts = [
        'height_cm' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'bee_custom' => 'decimal:2',
        'carb_percentage' => 'decimal:2',
        'protein_percentage' => 'decimal:2',
        'fat_percentage' => 'decimal:2',
        'belly' => 'decimal:2',
        'lower_abdomen' => 'decimal:2',
        'hips' => 'decimal:2',
        'right_arm' => 'decimal:2',
        'left_arm' => 'decimal:2',
        'right_thigh' => 'decimal:2',
        'left_thigh' => 'decimal:2',
        'right_calf' => 'decimal:2',
        'left_calf' => 'decimal:2',
    ];

    // Activity level mapping to f_value
    public function getFValueAttribute(): float
    {
        return match ($this->activity_level) {
            'light' => 1.2,
            'housewife' => 1.3,
            'student' => 1.4,
            'good_movement' => 1.5,
            'athletic' => 1.6,
            'very_athletic' => 1.7,
            default => 1.2,
        };
    }

    // Activity level display label
    public function getActivityLevelLabelAttribute(): string
    {
        return match ($this->activity_level) {
            'light' => 'Light Activity (نشاط خفيف)',
            'housewife' => 'Housewife (ربة منزل)',
            'student' => 'University Student (طالب جامعي)',
            'good_movement' => 'Good Movement (حركة جيدة)',
            'athletic' => 'Athletic Person (شخص رياضي)',
            'very_athletic' => 'Very Athletic (رياضي محترف)',
            default => 'Unknown',
        };
    }

    // For dropdown options
    public static function activityLevelOptions(): array
    {
        return [
            'light' => 'Light Activity (نشاط خفيف) - 1.2',
            'housewife' => 'Housewife (ربة منزل) - 1.3',
            'student' => 'University Student (طالب جامعي) - 1.4',
            'good_movement' => 'Good Movement (حركة جيدة) - 1.5',
            'athletic' => 'Athletic Person (شخص رياضي) - 1.6',
            'very_athletic' => 'Very Athletic (رياضي محترف) - 1.7',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function diet(): HasOne
    {
        return $this->hasOne(Diet::class);
    }

    // BMI Calculations
    public function getBmiAttribute(): float
    {
        $heightInMeters = $this->height_cm / 100;
        return round($this->weight_kg / ($heightInMeters * $heightInMeters), 2);
    }

    public function getFormattedBmiAttribute(): string
    {
        return number_format($this->getBmiAttribute(), 1);
    }

    public function getBmiCategoryAttribute(): string
    {
        $bmi = $this->getBmiAttribute();
        return match (true) {
            $bmi < 18.5 => 'Underweight',
            $bmi >= 18.5 && $bmi < 25 => 'Normal Weight',
            $bmi >= 25 && $bmi < 30 => 'Overweight',
            $bmi >= 30 && $bmi < 35 => 'Obesity Class I',
            $bmi >= 35 && $bmi < 40 => 'Obesity Class II',
            default => 'Obesity Class III',
        };
    }

    public function getBmiColorAttribute(): string
    {
        $bmi = $this->getBmiAttribute();
        return match (true) {
            $bmi < 18.5 => 'warning',
            $bmi >= 18.5 && $bmi < 25 => 'success',
            $bmi >= 25 && $bmi < 30 => 'info',
            default => 'danger',
        };
    }

    // BMR Calculation (Harris-Benedict)
    public function getBmrAttribute(): float
    {
        $weight = $this->weight_kg;
        $height = $this->height_cm;
        $age = $this->patient->age;

        if ($this->patient->gender) {
            return 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
        } else {
            return 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
        }
    }

    public function getFormattedBmrAttribute(): string
    {
        return number_format($this->getBmrAttribute(), 0);
    }

    // Energy Calculations
    public function bee(): float
    {
        return $this->getBmrAttribute() * $this->getFValueAttribute();
    }

    public function getFormattedBeeAttribute(): string
    {
        return number_format($this->bee(), 0);
    }

    public function aee(): float
    {
        return $this->bee() - ($this->bee_custom ?? 500);
    }

    public function getFormattedAeeAttribute(): string
    {
        return number_format($this->aee(), 0);
    }

    // Macronutrient Calculations using stored percentages
    public function carb(): float
    {
        return ($this->aee() * ($this->carb_percentage ?? 50)) / 100 / 4;
    }

    public function protein(): float
    {
        return ($this->aee() * ($this->protein_percentage ?? 20)) / 100 / 4;
    }

    public function fat(): float
    {
        return ($this->aee() * ($this->fat_percentage ?? 30)) / 100 / 9;
    }

    // Water Requirement
    public function water(): float
    {
        $weight = $this->weight_kg;
        if ($weight <= 30) {
            return 1750.0;
        } else {
            return 1750.0 + (($weight - 30) * 15);
        }
    }

    public function getFormattedWaterAttribute(): string
    {
        return number_format($this->water(), 0);
    }
}