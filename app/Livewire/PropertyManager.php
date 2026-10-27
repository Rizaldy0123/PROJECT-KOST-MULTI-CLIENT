<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertyManager extends Component
{
    public function render()
    {
        return view('livewire.property-manager', [
            'properties' => Property::all()
        ]);
    }
}