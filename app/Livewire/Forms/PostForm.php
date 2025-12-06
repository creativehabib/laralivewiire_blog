<?php

namespace App\Livewire\Forms;

use App\Models\Post;
use Livewire\Form;

class PostForm extends Form
{
    public ?Post $postModel;
    
    public $category_id = '';
    public $sub_category_id = '';
    public $user_id = '';
    public $video_playlist_id = '';
    public $title = '';
    public $slug = '';
    public $content_type = '';
    public $thumbnail_path = '';
    public $video_url = '';
    public $video_provider = '';
    public $video_id = '';
    public $video_source = '';
    public $video_embed_code = '';
    public $video_path = '';
    public $video_duration = '';
    public $description = '';
    public $is_featured = '';
    public $allow_comments = '';
    public $is_indexable = '';
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';

    public function rules(): array
    {
        return [
			'category_id' => 'required',
			'title' => 'required|string',
			'slug' => 'required|string',
			'content_type' => 'required|string',
			'thumbnail_path' => 'string',
			'video_url' => 'string',
			'video_provider' => 'string',
			'video_id' => 'string',
			'video_source' => 'string',
			'video_embed_code' => 'string',
			'video_path' => 'string',
			'video_duration' => 'string',
			'description' => 'required',
			'is_featured' => 'required',
			'allow_comments' => 'required',
			'is_indexable' => 'required',
			'meta_title' => 'string',
			'meta_description' => 'string',
			'meta_keywords' => 'string',
        ];
    }

    public function setPostModel(Post $postModel): void
    {
        $this->postModel = $postModel;
        
        $this->category_id = $this->postModel->category_id;
        $this->sub_category_id = $this->postModel->sub_category_id;
        $this->user_id = $this->postModel->user_id;
        $this->video_playlist_id = $this->postModel->video_playlist_id;
        $this->title = $this->postModel->title;
        $this->slug = $this->postModel->slug;
        $this->content_type = $this->postModel->content_type;
        $this->thumbnail_path = $this->postModel->thumbnail_path;
        $this->video_url = $this->postModel->video_url;
        $this->video_provider = $this->postModel->video_provider;
        $this->video_id = $this->postModel->video_id;
        $this->video_source = $this->postModel->video_source;
        $this->video_embed_code = $this->postModel->video_embed_code;
        $this->video_path = $this->postModel->video_path;
        $this->video_duration = $this->postModel->video_duration;
        $this->description = $this->postModel->description;
        $this->is_featured = $this->postModel->is_featured;
        $this->allow_comments = $this->postModel->allow_comments;
        $this->is_indexable = $this->postModel->is_indexable;
        $this->meta_title = $this->postModel->meta_title;
        $this->meta_description = $this->postModel->meta_description;
        $this->meta_keywords = $this->postModel->meta_keywords;
    }

    public function store(): void
    {
        $this->postModel->create($this->validate());

        $this->reset();
    }

    public function update(): void
    {
        $this->postModel->update($this->validate());

        $this->reset();
    }
}
