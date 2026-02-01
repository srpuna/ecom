<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];

    public function getEffectivePriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    // Format dimension value - remove .00 if whole number
    public function formatDimension($value)
    {
        if ($value == null) return 'N/A';
        return $value == floor($value) ? (int)$value : rtrim(rtrim(number_format($value, 2), '0'), '.');
    }

    public function getFormattedLengthAttribute()
    {
        return $this->formatDimension($this->length);
    }

    public function getFormattedWidthAttribute()
    {
        return $this->formatDimension($this->width);
    }

    public function getFormattedHeightAttribute()
    {
        return $this->formatDimension($this->height);
    }

    public function getFormattedWeightAttribute()
    {
        if ($this->weight == null) return 'N/A';
        return $this->weight == floor($this->weight) ? (int)$this->weight : rtrim(rtrim(number_format($this->weight, 3), '0'), '.');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }
}
