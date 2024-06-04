<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Mockery\Undefined;

class ColorPicker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title;
    public $name;
    public $value;
    public $removeGlassEffect;


    public function __construct($title = '', $name = '',  $value = [], $removeGlassEffect = false)
    {
        $this->title = $title;
        $this->name = $name;
        $this->value = $value;
        $this->removeGlassEffect = $removeGlassEffect;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.color-picker');
    }
}
