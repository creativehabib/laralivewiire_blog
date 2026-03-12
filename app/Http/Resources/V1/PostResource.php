<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'content' => $this->when($request->boolean('include_content', true), $this->content),
            'status' => $this->status,
            'image' => $this->image,
            'image_url' => $this->image_url,
            'views' => $this->views,
            'is_featured' => (bool) $this->is_featured,
            'is_breaking' => (bool) $this->is_breaking,
            'allow_comments' => (bool) $this->allow_comments,
            'format_type' => $this->format_type,
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
            ]),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'comments_count' => $this->whenCounted('comments'),
            'published_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
