<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'pipeline_id', 'type', 'certification_number', 'issued_date', 'expiry_date',
        'issuing_body', 'document_path', 'status',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
}