<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportIdeaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
                "title" => $this->title,
                "content" => $this->content,
                "user_name" => $this->user->name,
                "photo" => $this->user->photo,
                "department" => $this->user->department->department_name,
                "no_of_report" => $this->reports->count(),
                "hidden" => $this->hidden,
            ];

    }
}
