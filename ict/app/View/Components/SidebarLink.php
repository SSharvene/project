<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarLink extends Component
{
    public $route;
    public $label;

    public function __construct($route, $label)
    {
        $this->route = $route;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.sidebar-link');
    }
}
