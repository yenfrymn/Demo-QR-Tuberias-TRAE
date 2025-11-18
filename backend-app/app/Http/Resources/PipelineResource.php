<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PipelineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'qr_code' => $this->qr_code,
            'name' => $this->name,
            'location' => [
                'lat' => $this->lat,
                'lng' => $this->lng,
                'address' => $this->address,
            ],
            'specifications' => [
                'diameter' => $this->diameter,
                'material' => $this->material,
            ],
            'status' => $this->status,
            'description' => $this->description,
            'companies' => [
                'initiator' => optional($this->companies()->wherePivot('role', 'initiator')->first(), function ($c) {
                    return [
                        'id' => $c->id,
                        'name' => $c->name,
                        'start_date' => $c->pivot->start_date,
                    ];
                }),
                'current_operator' => optional($this->companies()->wherePivot('role', 'current_operator')->wherePivot('is_current', true)->first(), function ($c) {
                    $license = $this->operatingLicense;
                    return [
                        'id' => $c->id,
                        'name' => $c->name,
                        'start_date' => $c->pivot->start_date,
                        'license' => $license ? [
                            'number' => $license->license_number,
                            'issued_by' => $license->issued_by,
                            'expiry_date' => optional($license->expiry_date)->format('Y-m-d'),
                            'status' => $license->status,
                            'document_url' => $license->document_path,
                        ] : null,
                    ];
                }),
            ],
            'certifications' => $this->certifications->map(function ($cert) {
                return [
                    'id' => $cert->id,
                    'type' => $cert->type,
                    'number' => $cert->certification_number,
                    'issued_date' => optional($cert->issued_date)->format('Y-m-d'),
                    'expiry_date' => optional($cert->expiry_date)->format('Y-m-d'),
                    'status' => $cert->status,
                    'document_url' => $cert->document_path,
                ];
            }),
            'blueprints' => $this->blueprints->map(function ($bp) {
                return [
                    'id' => $bp->id,
                    'title' => $bp->title,
                    'file_type' => $bp->file_type,
                    'version' => $bp->version,
                    'upload_date' => optional($bp->upload_date)->format('Y-m-d'),
                    'download_url' => $bp->file_path,
                ];
            }),
        ];
    }
}