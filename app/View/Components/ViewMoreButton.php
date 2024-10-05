<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ViewMoreButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $route;
    public $label;
    
    public function __construct($route, $label = 'View More')
    {
        $this->route = $route;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.view-more-button');
    }
}
