<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'posts_count' => $this->whenCounted('posts'),
            'children_count' => $this->whenCounted('children'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
