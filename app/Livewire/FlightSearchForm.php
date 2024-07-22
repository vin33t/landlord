<?php

namespace App\Livewire;

use App\Models\Airport;
use Livewire\Component;

class FlightSearchForm extends Component
{


    public $originQuery = '';
    public $destinationQuery = '';
    public $originAirports = [];
    public $destinationAirports = [];

    public function updatedOriginQuery()
    {
        dd($this->originQuery);
        $this->originAirports = Airport::where('name', 'like', '%'.$this->originQuery.'%')
            ->orWhere('city', 'like', '%'.$this->originQuery.'%')
            ->orWhere('iata', 'like', '%'.$this->originQuery.'%')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function updatedDestinationQuery()
    {
        $this->destinationAirports = Airport::where('name', 'like', '%'.$this->destinationQuery.'%')
            ->orWhere('city', 'like', '%'.$this->destinationQuery.'%')
            ->orWhere('iata', 'like', '%'.$this->destinationQuery.'%')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function setOrigin($iata)
    {
        $this->originQuery = $iata;
        $this->reset('originAirports');
    }

    public function setDestination($iata)
    {
        $this->destinationQuery = $iata;
        $this->reset('destinationAirports');
    }

    public function render()
    {
        return view('livewire.flight-search-form');
    }
}
