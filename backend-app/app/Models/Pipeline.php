<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code', 'name', 'lat', 'lng', 'address', 'diameter', 'material',
        'installation_date', 'status', 'description',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'pipeline_companies')
            ->withPivot(['role', 'start_date', 'end_date', 'is_current'])
            ->withTimestamps();
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function operatingLicense()
    {
        return $this->hasOne(OperatingLicense::class)->latestOfMany('expiry_date');
    }

    public function blueprints()
    {
        return $this->hasMany(Blueprint::class);
    }

    public function inspectionReports()
    {
        return $this->hasMany(InspectionReport::class);
    }

    public function scopeQr($query, string $code)
    {
        return $query->where('qr_code', $code);
    }
}