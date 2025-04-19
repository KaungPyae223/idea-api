<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportedUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $hasPermission = $this->permissions()->whereIn('permission_id', [12, 13])->exists();

        return [

            "name" => $this->name,
            "photo" => $this->photo,
            "email" => $this->email,
            "department" => $this->department->department_name,
            "email" => $this->email,
            "hidden" => $this->hidden,
            "no_of_reports" => $this->reports_count,
            "banned" => !$hasPermission
        ];
    }
}
