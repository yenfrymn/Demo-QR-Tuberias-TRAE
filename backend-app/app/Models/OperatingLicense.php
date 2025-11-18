<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'pipeline_id', 'license_number', 'issued_by', 'issue_date', 'expiry_date',
        'status', 'document_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
}