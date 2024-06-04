<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RegionsInput extends Component
{
    public $regions = [];
    public $countries = [];
    public $mode;
    public $shippingregions = [];
    public $event = null;
    public $disableCard = false;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($countries, $regions,  $event = null, $shippingregions = [], $mode = "create", $disableCard = false)
    {
        $this->countries = $countries;
        $this->regions = $regions;
        $this->mode = $mode;
        $this->shippingregions = $shippingregions;
        $this->event = $event;
        $this->disableCard = $disableCard;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.regions-input');
    }
}
