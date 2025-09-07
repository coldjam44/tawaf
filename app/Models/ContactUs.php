<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name_ar',
        'company_name_en',
        'broker_registration_number',
        'phone_numbers',
        'email_addresses',
        'locations',
        'map_embed',
    ];

    protected $casts = [
        'phone_numbers' => 'array',
        'email_addresses' => 'array',
        'locations' => 'array',
    ];

    /**
     * Get company name based on current locale
     */
    public function getCompanyNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->company_name_ar : $this->company_name_en;
    }

    /**
     * Get phone numbers as array
     */
    public function getPhoneNumbersArrayAttribute()
    {
        return $this->phone_numbers ?? [];
    }

    /**
     * Get email addresses as array
     */
    public function getEmailAddressesArrayAttribute()
    {
        return $this->email_addresses ?? [];
    }

    /**
     * Get locations as array
     */
    public function getLocationsArrayAttribute()
    {
        return $this->locations ?? [];
    }
}
