<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class HtaccessSettings extends Component
{
    public $rootContent = '';
    public $publicContent = '';

    // ১. লারাভেলের স্ট্যান্ডার্ড পাবলিক .htaccess কোড
    private string $defaultPublic = <<<'EOT'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOT;

    // ২. রুট ফোল্ডারের জন্য স্ট্যান্ডার্ড রিডাইরেক্ট কোড (Shared Hosting এর জন্য)
    private string $defaultRoot = <<<'EOT'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
EOT;

    public function mount()
    {
        // 1. Root .htaccess (base_path)
        $rootPath = base_path('.htaccess');
        if (File::exists($rootPath)) {
            $this->rootContent = File::get($rootPath);
        } else {
            // ফাইল না থাকলে ডিফল্ট বা কমেন্ট দেখানো হবে
            $this->rootContent = $this->defaultRoot;
        }

        // 2. Public .htaccess (public_path)
        $publicPath = public_path('.htaccess');
        if (File::exists($publicPath)) {
            $this->publicContent = File::get($publicPath);
        } else {
            $this->publicContent = $this->defaultPublic;
        }
    }

    public function save()
    {
        $this->validate([
            'rootContent' => 'nullable|string',
            'publicContent' => 'required|string',
        ]);

        try {
            // Save Root .htaccess
            File::put(base_path('.htaccess'), $this->rootContent);

            // Save Public .htaccess
            File::put(public_path('.htaccess'), $this->publicContent);

            $this->dispatch('media-toast', type: 'success', message: 'Both .htaccess files updated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('media-toast', type: 'error', message: 'Failed to save files! Check permissions.');
        }
    }

    /**
     * Restore Default Functionality
     * ব্লেড ফাইল থেকে call করা হবে যখন ইউজার Restore বাটনে ক্লিক করবে
     */
    public function restoreDefaults($type)
    {
        $content = '';

        if ($type === 'public') {
            $this->publicContent = $this->defaultPublic;
            $content = $this->publicContent;
        } elseif ($type === 'root') {
            $this->rootContent = $this->defaultRoot;
            $content = $this->rootContent;
        }

        // জাভাস্ক্রিপ্ট ইভেন্ট ডিসপ্যাচ করা যাতে CodeMirror আপডেট হয়
        $this->dispatch('htaccess-restored', type: $type, content: $content);

        $this->dispatch('media-toast', type: 'info', message: ucfirst($type) . ' .htaccess restored to default.');
    }

    public function render()
    {
        return view('livewire.admin.settings.htaccess-settings')
            ->layout('components.layouts.app', ['title' => '.htaccess Editor']);
    }
}
