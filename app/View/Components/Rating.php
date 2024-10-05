<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Rating extends Component
{
    /**
     * Create a new component instance.
     */
    public $averageRating;
    
    public function __construct(float $averageRating)
    {
        $this->averageRating = $averageRating;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rating');
    }
}
