<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $selected = [];
    public $selectAll = false;
    public $showActivity = true;
    public $showIp = true;
    public $showBrowser = true;
    public $showOs = true;
    public $retentionDays = 15;

    public function mount(): void
    {
        $this->showActivity = (bool) setting('activity_logs_show_activity', true);
        $this->showIp = (bool) setting('activity_logs_show_ip', true);
        $this->showBrowser = (bool) setting('activity_logs_show_browser', true);
        $this->showOs = (bool) setting('activity_logs_show_os', true);
        $this->retentionDays = (int) setting('activity_logs_retention_days', 15);
    }

    // বাল্ক ডিলিট
    public function deleteSelected()
    {
        Activity::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->selectAll = false;
        $this->dispatch('media-toast', type: 'success', message: 'Selected logs deleted!');
    }

    // সব ডিলিট
    public function deleteAll()
    {
        Activity::truncate();
        $this->dispatch('media-toast', type: 'success', message: 'All records deleted!');
    }

    // সিঙ্গেল ডিলিট
    public function delete($id)
    {
        Activity::find($id)->delete();
        $this->dispatch('media-toast', type: 'success', message: 'Log deleted successfully!');
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'showActivity' => ['boolean'],
            'showIp' => ['boolean'],
            'showBrowser' => ['boolean'],
            'showOs' => ['boolean'],
            'retentionDays' => ['required', 'integer', 'min:0', 'max:3650'],
        ]);

        set_setting('activity_logs_show_activity', (bool) $validated['showActivity'], 'system');
        set_setting('activity_logs_show_ip', (bool) $validated['showIp'], 'system');
        set_setting('activity_logs_show_browser', (bool) $validated['showBrowser'], 'system');
        set_setting('activity_logs_show_os', (bool) $validated['showOs'], 'system');
        set_setting('activity_logs_retention_days', (int) $validated['retentionDays'], 'system');

        $this->dispatch('media-toast', type: 'success', message: 'Activity log settings saved!');
    }

    public function resolveBrowser(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        $map = [
            'Edg' => 'Edge',
            'Opera' => 'Opera',
            'OPR' => 'Opera',
            'Firefox' => 'Firefox',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
        ];

        foreach ($map as $needle => $label) {
            if (Str::contains($userAgent, $needle)) {
                return $label;
            }
        }

        return 'Other';
    }

    public function resolveOs(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        $ua = Str::lower($userAgent);

        if (Str::contains($ua, ['iphone', 'ipad'])) {
            return 'iOS';
        }

        if (Str::contains($ua, 'android')) {
            return 'Android';
        }

        if (Str::contains($ua, ['macintosh', 'mac os'])) {
            return 'macOS';
        }

        if (Str::contains($ua, 'windows')) {
            return 'Windows';
        }

        if (Str::contains($ua, 'linux')) {
            return 'Linux';
        }

        return 'Other';
    }

    // Select All লজিক
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getLogsQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function getLogsQuery()
    {
        return Activity::with('causer')
            ->where(function($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('causer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest();
    }

    public function render()
    {
        return view('livewire.admin.settings.activity-logs', [
            'logs' => $this->getLogsQuery()->paginate(20)
        ])->layout('components.layouts.app', ['title' => 'Activity Logs']);
    }
}
