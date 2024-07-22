<?php

namespace vin33t\HotelBooking\Contracts;

interface BookingInterface
{
    public function search(string $location, string $checkIn, string $checkOut, array $guestsArray);
}
