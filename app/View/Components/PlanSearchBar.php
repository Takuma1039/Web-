<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class PlanSearchBar extends Component
{
    /**
     * Create a new component instance.
     */
    public Collection $plantypes;
    public Collection $locals;
    public Collection $seasons;
    public Collection $months;
    
    public function __construct(Collection $plantypes, Collection $locals, Collection $seasons, Collection $months)
    {
        $this->plantypes = $plantypes;
        $this->locals = $locals;
        $this->seasons = $seasons;
        $this->months = $months;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.plan-search-bar');
    }
}
