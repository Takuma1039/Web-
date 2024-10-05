<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReviewLike extends Component
{
    /**
     * Create a new component instance.
     */
    public $review;
    public $reviewImages;
    
    public function __construct($review, $reviewImages)
    {
        $this->review = $review;
        $this->reviewImages = $reviewImages;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.review-like');
    }
}
