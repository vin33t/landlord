<?php

namespace App\Livewire;

use Livewire\Component;

class HotelSearch extends Component
{
    public $country;
    public $cityIds;
    public $checkInDate;
    public $checkOutDate;
    public $rooms = [];
    public $nationality;
    public $currency;
    public $availableOnly = 0;
    public $hotels = [];

    public $roomCount = 1;

    public function mount()
    {
        $this->rooms = [
            ['NumAdults' => 1, 'Children' => '']
        ];
    }

    public function searchHotels()
    {
        $params = [
            'CityIds' => $this->cityIds,
            'CheckInDate' => $this->checkInDate,
            'CheckOutDate' => $this->checkOutDate,
            'Rooms' => $this->rooms,
            'Nationality' => $this->nationality,
            'Currency' => $this->currency,
            'AvailableOnly' => $this->availableOnly,
        ];

        // Example API call to search hotels
        // $this->hotels = HotelBooking::search($params);
    }

    public function render()
    {
        return view('livewire.hotel-search');
    }
}
