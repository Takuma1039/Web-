<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MapModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $apiKey;
    public $latitude;
    public $longitude;
    public $spotName;
    
    public function __construct($apiKey, $latitude, $longitude, $spotName)
    {
        $this->apiKey = $apiKey;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->spotName = $spotName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.map-modal');
    }
}
