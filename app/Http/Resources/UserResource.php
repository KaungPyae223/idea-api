<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "department" => $this->department?$this->department->department_name:null,
            "roles" => $this->roles->pluck('role')->toArray(),
            "permissions" => $this->permissions->pluck('permission')->toArray()
        ];
    }
}
