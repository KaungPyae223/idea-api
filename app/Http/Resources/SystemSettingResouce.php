<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemSettingResouce extends JsonResource
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
            "idea_closure_date" => $this->idea_closure_date,
            "final_closure_date" => $this->final_closure_date,
            "academic_year" => $this->academic_year,
            "status" => $this->status,
            "total_ideas" => $this->ideas->count()

        ];
    }
}
