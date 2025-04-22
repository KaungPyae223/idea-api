<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            "department_name" => $this->department_name,
            "qa_coordinator_name" => $this->qaCoordinator->name,
            "total_user" => $this->user->count(),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,

        ];
    }
}
