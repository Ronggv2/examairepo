<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
      public $menus = [];

    /**
     * Controls whether the mobile sidebar stays open across Livewire updates.
     */
    public $sidebarOpen = false;

    protected $listeners = [
        'toggleSidebar' => 'toggleSidebar',
        'closeSidebar' => 'closeSidebar',
    ];

    // ✅Menu items with route & icon
    public $menuItems = [
        [
            'title' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'assets/svg/dashboard.svg',
        ],
        [
            'title' => 'Access Control',
            'key' => 'accessMenu',
            'icon' => 'assets/svg/user.svg',
            'children' => [
                [
                    'title' => 'Users Accounts',
                    'route' => 'users',
                    'icon' => 'assets/svg/account.svg',
                ],
            ]
        ],
       [
            'title' => 'AI Logs',
            'route' => 'ailog',
            'icon' => 'assets/svg/ai.svg',
        ],
               [
            'title' => 'Logout',
            'action' => 'logout',
            'icon' => 'assets/svg/logout.svg',
        ],
        
    ];

    // Load SVG files on component mount and restore UI state from session
    public function mount()
    {
        $this->sidebarOpen = session('sidebarOpen', false);
        $this->menus = session('sidebarMenus', []);

        $this->loadSvgIcons($this->menuItems);
    }

    // Recursively load SVG content from files
    private function loadSvgIcons(&$items)
    {
        foreach ($items as &$item) {
            if (isset($item['icon']) && !str_contains($item['icon'], '<svg')) {
                $svgPath = public_path($item['icon']);
                if (file_exists($svgPath)) {
                    $svgContent = file_get_contents($svgPath);
                    // Add Tailwind classes for proper sizing
                    $class = isset($item['children']) ? 'w-4 h-4 mr-1' : 'w-5 h-5 mr-2';
                    $svgContent = str_replace('<svg', '<svg class="' . $class . '"', $svgContent);
                    $item['icon'] = $svgContent;
                }
            }
            if (isset($item['children'])) {
                $this->loadSvgIcons($item['children']);
            }
        }
    }
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }

    // Toggle dropdown menu
    public function toggleMenu($menu)
    {
        $this->menus[$menu] = !($this->menus[$menu] ?? false);
        session(['sidebarMenus' => $this->menus]);
    }

    // Toggle mobile sidebar
    public function toggleSidebar()
    {
        $this->sidebarOpen = ! $this->sidebarOpen;
        session(['sidebarOpen' => $this->sidebarOpen]);
    }

    // Close mobile sidebar
    public function closeSidebar()
    {
        $this->sidebarOpen = false;
        session(['sidebarOpen' => false]);
    }

    // Check if dropdown is expanded
    public function isExpanded($menu)
    {
        return $this->menus[$menu] ?? false;
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
