<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdeaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $department_name =  $this->user->department? $this->user->department->department_name:null;

        $user_vote = $this->votes()->where("user_id", 1)->first();

        $user_vote_value = $user_vote ? $user_vote->vote_value : 0;

        return [
            "id" => $this->id,
            "user_name" => $this->is_anonymous? "Anonymous" : $this->user->name,
            "user_email" => $this->is_anonymous? "" : $this->user->email,
            "user_photo" => $this->is_anonymous? "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Breezeicons-actions-22-im-user.svg/1200px-Breezeicons-actions-22-im-user.svg.png" : $this->user->photo,
            "department" => $this->is_anonymous? "" : $department_name ,
            "title" => $this->title,
            "content" => $this->content,
            "category" => $this->categories->pluck("name")->toArray(),
            "files" => $this->files,
            "comments" => $this->comment->count(),
            "total_vote_value" => $this->votes->count()?$this->votes->sum("vote_value"):0,
            "user_vote_value" => $user_vote_value,
            "time" => $this->created_at,
        ];
    }
}
