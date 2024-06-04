<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ColorSettingsPanel extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $title;
    public $itemList;

    public function __construct($title, $itemList)
    {
        $this->title = $title;
        $this->itemList = $itemList;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.color-settings-panel');
    }
}
