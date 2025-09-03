<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $primaryKey = 'propertyid';

    protected $fillable = [
        'propertyproject',
        'propertypurpose',
        'propertyimages',
        'propertyprice',
        'propertyrooms',
        'propertybathrooms',
        'propertyarea',
        'propertyquantity',
        'propertyloaction',
        'propertypaymentplan',
        'propertyhandover',
        'propertyfeatures',
        'propertyfulldetils',
        'propertyinformation',
    ];

    protected $casts = [
        'propertyimages' => 'array',
        'propertyfeatures' => 'array',
        'propertyprice' => 'decimal:2',
        'propertyarea' => 'decimal:2',
        'propertyquantity' => 'integer',
        'propertyhandover' => 'date',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'propertyproject');
    }

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class, 'propertypaymentplan');
    }

    // Helper methods
    public function getLocalizedTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getFormattedPriceAttribute()
    {
        return 'AED ' . number_format($this->propertyprice, 2);
    }

    public function getFormattedAreaAttribute()
    {
        return number_format($this->propertyarea, 2) . ' sqm';
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->propertyquantity);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('propertyproject', $projectId);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('propertyloaction', 'like', "%{$location}%");
    }

    public function scopeByPaymentPlan($query, $paymentPlanId)
    {
        return $query->where('propertypaymentplan', $paymentPlanId);
    }
}
