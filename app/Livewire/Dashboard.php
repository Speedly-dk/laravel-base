<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $monitors = [];
    public $stats = [];

    public function mount()
    {
        // Sample data for demonstration
        $this->stats = [
            'total_monitors' => 12,
            'online' => 10,
            'offline' => 2,
            'avg_response_time' => '245ms',
            'uptime_percentage' => '99.92%',
        ];

        $this->monitors = [
            [
                'id' => 1,
                'name' => 'API Server',
                'url' => 'https://api.example.com',
                'status' => 'online',
                'response_time' => '120ms',
                'uptime' => '99.99%',
            ],
            [
                'id' => 2,
                'name' => 'Website',
                'url' => 'https://example.com',
                'status' => 'online',
                'response_time' => '85ms',
                'uptime' => '100%',
            ],
            [
                'id' => 3,
                'name' => 'Database Server',
                'url' => 'mysql://db.example.com',
                'status' => 'offline',
                'response_time' => '-',
                'uptime' => '98.5%',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
