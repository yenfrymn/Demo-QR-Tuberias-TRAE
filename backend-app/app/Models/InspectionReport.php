<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'pipeline_id', 'inspector_id', 'report_date', 'report_type', 'findings', 'document_path',
    ];

    protected $casts = [
        'report_date' => 'date',
        'findings' => 'array',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
}