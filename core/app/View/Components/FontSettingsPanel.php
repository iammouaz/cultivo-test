<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FontSettingsPanel extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $title;
    public $itemList;
    public $type;

    public function __construct($title, $itemList, $type = null)
    {
        $this->title = $title;
        $this->itemList = $itemList;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.font-settings-panel');
    }
}
