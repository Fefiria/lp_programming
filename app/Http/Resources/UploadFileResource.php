<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadFileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_file'    => $this->id_file,
            'name'       => $this->name,
            'path'       => $this->path,
            'type'       => $this->type,
            'size'       => $this->size,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
