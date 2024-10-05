<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImagePreview extends Component
{
    /**
     * Create a new component instance.
     */
    public $inputName; // 画像入力の名前
    
    public function __construct(string $inputName = 'images[]')
    {
        $this->inputName = $inputName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.image-preview');
    }
}
