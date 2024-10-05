<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SpotCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $spot;
    
    public function __construct($spot)
    {
        $this->spot = $spot;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.spot-card');
    }
}
