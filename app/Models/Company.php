<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'description',
        'logo',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'tax_number',
        'business_license',
        'company_type',
        'established_date',
        'employee_count',
        'social_media',
        'is_active'
    ];

    protected $casts = [
        'social_media' => 'array',
        'established_date' => 'date',
        'is_active' => 'boolean'
    ];
}
