<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name'];

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class);
    }

    public function canBeDeleted(): bool
    {
        return $this->foodItems()->count() === 0;
    }

}
