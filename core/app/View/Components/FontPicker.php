<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FontPicker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title;
    public $name;
    public $value;
    public $style;
    public $letterspacing;
    public $texttransform;
    public $stylevalue;
    public $letterspacingvalue;
    public $texttransformvalue;
    public $custom_corners;


    public function __construct($title, $name, $value = [], $style,  $letterspacing, $texttransform, $stylevalue,  $letterspacingvalue, $texttransformvalue, $custom_corners = null)
    {
        $this->title = $title;
        $this->name = $name;
        $this->value = $value;
        $this->style = $style;
        $this->letterspacing = $letterspacing;
        $this->texttransform = $texttransform;
        $this->stylevalue = $stylevalue;
        $this->letterspacingvalue = $letterspacingvalue;
        $this->texttransformvalue = $texttransformvalue;
        $this->custom_corners = $custom_corners;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.font-picker');
    }
}
