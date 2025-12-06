<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class PostCreate extends Component
{
    public $seo_image;       // uploaded file
    public $seo_image_path;

    public function render()
    {
        return view('livewire.admin.post-create');
    }
}
