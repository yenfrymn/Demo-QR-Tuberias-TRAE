<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blueprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'pipeline_id', 'title', 'file_path', 'file_type', 'version', 'upload_date', 'uploaded_by',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
}