<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $selected = [];
    public $selectAll = false;

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
