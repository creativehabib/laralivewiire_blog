<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when($request->boolean('include_email', false), $this->email),
            'website' => $this->website,
            'content' => $this->content,
            'status' => $this->status,
            'avatar_url' => $this->avatar_url,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'parent_id' => $this->parent_id,
            'commentable' => [
                'id' => $this->commentable_id,
                'type' => class_basename((string) $this->commentable_type),
            ],
            'replies_count' => $this->whenCounted('replies'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
