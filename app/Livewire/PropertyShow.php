<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyShow extends Component
{
    public Property $property;

    public function mount(Property $property) {
        $this->property = $property;
    }
    
    public function render()
    {
        return view('livewire.property-show');
    }
}
