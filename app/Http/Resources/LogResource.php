<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user" => $this->user->name,
            "email" => $this->user->email,
            "photo" => $this->user->photo,
            "type" => $this->type,
            "action" => $this->action,
            "activity" => $this->activity,
            "time" => $this->created_at,
        ];
    }
}
