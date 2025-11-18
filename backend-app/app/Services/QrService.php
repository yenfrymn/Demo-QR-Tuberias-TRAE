<?php

namespace App\Services;

use App\Models\Pipeline;
use Illuminate\Support\Facades\Storage;

class QrService
{
    public function ensureCode(Pipeline $pipeline): string
    {
        if ($pipeline->qr_code) return $pipeline->qr_code;
        $code = sprintf('PIPE-%s-%03d', now()->year, $pipeline->id);
        $pipeline->qr_code = $code;
        $pipeline->save();
        return $code;
    }

    public function checksum(string $code): string
    {
        return substr(sha1($code), 0, 12);
    }

    public function generateImage(string $code): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect width="100%" height="100%" fill="#fff"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="16" fill="#000">QR: '.$code.'</text></svg>';
        $path = 'public/qr/'.str_replace(['/',':'], '-', $code).'.svg';
        Storage::put($path, $svg);
        return $path;
    }
}