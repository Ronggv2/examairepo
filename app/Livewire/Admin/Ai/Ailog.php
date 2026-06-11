<?php

namespace App\Livewire\Admin\Ai;

use App\Models\AiGeneration;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Ailog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $logs = AiGeneration::with('user', 'questionSet')
            ->latest()
            ->paginate(10);

        // Ensure user names are available
        $logs->getCollection()->transform(function ($log) {
            if (!$log->user && $log->user_id) {
                $log->user = User::find($log->user_id);
            }
            return $log;
        });

        return view('livewire.admin.ai.ailog', [
            'logs' => $logs,
        ])->layout('components.layouts.app', [
            'title' => 'AI Generation Logs',
            'showLoader' => false,
        ]);
    }
}
